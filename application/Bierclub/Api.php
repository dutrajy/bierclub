<?php

namespace Commercial\Application\Bierclub;

use Commercial\Application\Financial\Account;
use Commercial\Framework\Database\Connection;
use Commercial\Application\Sales\Sale;
use Commercial\Application\Products\Product;
use Commercial\Application\Financial\AccountOperation;
use Commercial\Application\Users\User;

use Commercial\Application\Printer\Printer;

/** @Controller("/api/bierclub") */
class Api extends \Commercial\Application\Common\Controller
{
    /** @Get("/member/{card}") */
    public function getMemberByCard($request, $card) {

        $card = \urldecode($card);
        $member = Member::findOne(["card" => $card]);

        if ($member && $member->getActive()) {
            $balance = $member->getBalance();
            $member = $member->getReadableData();
            $member["balance"] = $balance;
            return $member;
        } else {
            $admin = User::findOne(["card" => $card, "role" => "administrator"])->getReadableData();
            return $admin;
        }

        return null;
    }

    /** @Post("/buy") */
    public function buy($request)
    {
        try {
            Connection::beginTransaction();
            $member = Member::findOne(["card" => $request->getParsedBody()["member"]["card"], "active" => 1]);

            $sale = new Sale([
                "observations" => "Realized on bierclub client by member: {$member->getFullName()} #{$member->getId()}"
            ]);


            foreach ($request->getParsedBody()["cart"] as $product) {
                $sale->addProduct(
                    Product::findOne(["id" => $product["id"]]),
                    (int) $product["quantity"]
                );
            }

            $sale->setCreator($this->user);
            $sale->insert();

            if ($sale->getTotal() > $member->getBalance()) {
                Connection::rollBack();
                return ["success" => false, "msg" => "Saldo insuficiente!"];
            }


            if ($this->user->getRole() === "manager" || $this->user->getRole() === "administrator") {
                $account = $member->getAccount();

                $operation = new AccountOperation();
                $operation->setDescription("Sale #{$sale->getId()}");
                $operation->setCreator($this->user);
                $operation->setAmount(- $sale->getTotal());
                $operation->setAccount($account);

                $operation->insert();
            } else {
                return ["success" => false, "msg" => "error: Operation Not Allowed"];
            }

            Connection::commit();

            $cupom = "------------------------\n";
            $cupom .= "---- 403  Bier Club ----\n";
            $cupom .= "------------------------\n\n";

            $memberName = substr($member->getFullName(), 0, 19);

	    $memberIdText = str_pad($member->getId(), 3, "0", STR_PAD_LEFT);
            $cupom .= "{$memberName} #{$memberIdText}\n";
            $cupom .= "Venda #{$sale->getId()}\n\n";

            foreach ($sale->getItems() as $item) {

                $productName = substr($item->getProduct()->getName(), 0, 15);

                for ($i=0; $i < $item->getQuantity(); $i++) {
                    $cupom .= "[  ] $productName\n";
                }
            }

            $cupom .= "\n\n------------------------\n\n\n";
            Printer::print($cupom);

            return ["success" => true, "msg" => "Compra realizada com sucesso!"];
        } catch (\Exception $e) {
            return ["success" => false, "msg" => "error: {$e->getMessage()}"];
        }
    }

    /** @Post("/member/{id}/credit") */
    public function credit($request, $id)
    {
        $adminCard = $request->getParsedBody()["adminCard"];

        $amount = $request->getParsedBody()["amount"];
        $amount = str_replace(".", "", $amount);
        $amount = str_replace(",", ".", $amount);

        if (($this->user->getRole() === "manager" || $this->user->getRole() === "administrator") && $this->user->getCard() === $adminCard) {
            try {
                Connection::beginTransaction();
                $member = Member::findOne(["id" => $id]);
                $account = $member->getAccount();

                $operation = new AccountOperation();
                $operation->setDescription("Credit inserted by {$this->user->getRole()} - {$this->user->getFullName()} #{$this->user->getId()}");
                $operation->setCreator($this->user);
                $operation->setAmount($amount);
                $operation->setAccount($account);

                $operation->debitFrom(Account::findOne(["owner_type" => "users", "owner_id" => $this->user->getId()]));

                Connection::commit();

                return ["success" => true];
            } catch(Exception $e) {
                Connection::rollBack();
                return ["success" => false];
            }

        } else {
            return ["success" => false, "msg" => "Operação não permitida!"];
        }
    }

    /** @Post("/mercado_pago/pay") */
    public function mercadoPagoPay($request)
    {
        $amount = $request->getParsedBody()["amount"];
        $amount = str_replace(".", "", $amount);
        $amount = str_replace(",", ".", $amount);

        $payment = new \MercadoPago\Payment();
        $payment->transaction_amount = $amount;
        $payment->token = $request->getParsedBody()["token"];
        $payment->description = "Bierclub: " . $request->getParsedBody()["email"];
        $payment->installments = 1;
        $payment->payment_method_id = $request->getParsedBody()["paymentMethodId"];
        $payment->payer = array(
            "email" => $request->getParsedBody()["email"]
        );

        $payment->save();

        $out = "<body>";
        $out .= "<br/><br/><br/>";

        if ($payment->status === "approved") {
            try {
                $marcadoPagoUser = User::findOne(["username" => "mercado_pago"]);
                Connection::beginTransaction();
                $account = Account::findOne(["owner_type" => "bierclub_members", "owner_id" => $request->getParsedBody()["bierclub_member_id"]]);

                $operation = new AccountOperation();
                $operation->setDescription("Credit inserted by {$marcadoPagoUser->getRole()} - {$marcadoPagoUser->getFullName()} #{$marcadoPagoUser->getId()}");
                $operation->setCreator($this->user);
                $operation->setAmount($amount);
                $operation->setAccount($account);

                $operation->debitFrom(Account::findOne(["owner_type" => "users", "owner_id" => $marcadoPagoUser->getId()]));

                Connection::commit();

                $out .= "Pagamento relaizado com sucesso!<br/>";
            } catch(Exception $e) {
                Connection::rollBack();
                $out .= "O pagamento foi aprovado pela operadora mas ouve um erro ao salvar no banco de dados<br/>";
                $out .= "Fale com o gerente para o extorno (Gerente: Chame o desenvolvedor!!!)<br/>";
            }

        } else {

            $out .= "Operação não pode ser concluída<br/>";
            $out .= "Por favor consulte o gerente<br/>";
            $out .= "Status da Resposta: " . $payment->status . "<br/><br/>";
        }

        $out .= '<a href="https://403bierclub.ml/clients/bierclub/index.html"><button>Voltar para o 403 Bier Club</button></a>';
        $out .= "</body>";
        $out .= "</html>";

        return $out;
    }
}
