<?php

namespace Commercial\Application\Sales;

use Commercial\Framework\Database\Connection;
use Commercial\Application\Products\Product;

/** @Controller */
class Crud extends \Commercial\Application\Common\Controller
{
    /** @Get("/sales") */
    public function list()
    {
        return $this->render(
            "Sales/List",
            [
                "sales" => Sale::findAll()
            ]
        );
    }

    /** @Get("/sales/new") */
    public function new()
    {
        return $this->render(
            "Sales/Form",
            [
                "title" => "Nova Venda",
                "sale" => new Sale(),
                "items" => [],
            ]
        );
    }

    /** @Get("/sales/edit/{id}") */
    public function edit($request, $id)
    {
        return $this->render(
            "Sales/Form",
            [
                "title" => "Editar Venda",
                "sale" => Sale::findOne(["id" => $id]),
                "items" => Item::findAll(["sale_id" => $id]),
            ]
        );
    }

    /** @Post("/sales/create") */
    public function create($request)
    {
        Connection::beginTransaction();
        $sale = new Sale([
            "observations" => $request->getParsedBody()["sale"]["observations"]
        ]);

        foreach ($request->getParsedBody()["products"] as $id => $quantity) {
            $sale->addProduct(
                Product::findOne(["id" => $id]),
                (int) $quantity
            );
        }

        $sale->setCreator($this->user);

        $sale->insert();
        Connection::commit();

        $this->redirect("/sales");
    }

    /** @Any("/sales/delete") */
    public function delete()
    {
        $id = $this->get("id");
        $sale = Sale::findOne(["id" => $id]);

        foreach ($sale->getItems() as $item) {
            $item->delete();
        }

        $sale->delete();

        return "Venda #{$id} deletado com sucesso";
    }
}
