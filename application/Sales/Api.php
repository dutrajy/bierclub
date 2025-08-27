<?php

namespace Commercial\Application\Sales;

/** @Controller */
class Api extends \Commercial\Application\Common\Controller
{
    public function __construct()
    {
        Session::start();

        if (!Session::get("signed")) {
            $this->redirect("/auth");
        } else {
            $this->user = User::findOne(["id" => Session::get("user_id")]);
        }
    }

    /** @Post("/api/sales") */
    public function create($request)
    {
        try {
            Connection::beginTransaction();
            $sale = new Sale([
                "observations" => "Relized on bierclub client"
            ]);

            foreach ($request->getParsedBody() as $product) {
                $sale->addProduct(
                    Product::findOne(["id" => $product->id]),
                    (int) $product->quantity
                );
            }

            $sale->setCreator($this->user);
            $sale->setUpdater($this->user);

            $sale->insert();
            Connection::commitTransaction();

            $this->json(["success" => true, "msg" => "Success saved"]);
        } catch (Exception $e) {
            $this->json(["success" => false, "msg" => "error: {$e->getMessage()}"]);
        }
    }
}
