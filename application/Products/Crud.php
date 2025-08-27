<?php

namespace Commercial\Application\Products;

/** @Controller("/products") */
class Crud extends \Commercial\Application\Common\Controller
{
    /** @Any */
    public function list()
    {
        return $this->render(
            "Products/List",
            [
                "products" => Product::findAll()
            ]
        );
    }

    /** @Any("/new") */
    public function new()
    {
        return $this->render(
            "Products/Form",
            [
                "action" => "create",
                "title" => "Novo Produto",
                "product" => new Product(),
                "categories" => Category::findAll(),
            ]
        );
    }

    /** @Any("/edit/{id}") */
    public function edit($request, $id)
    {
        return $this->render(
            "Products/Form",
            [
                "action" => "update",
                "title" => "Editar Produto",
                "product" => Product::findOne(["id" => $id]),
                "categories" => Category::findAll(),
            ]
        );
    }

    /** @Post("/create") */
    public function create($request)
    {
        $data = Product::sanitize($request->getParsedBody()["product"]);
        $product = new Product($data);

        if (!empty($request->getParsedBody()["product_image"])) {
            $targetDirectory = \realpath(__DIR__ . "/../../public/images/uploads/");
            $img = $request->getParsedBody()["product_image"];
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = md5(uniqid()) .'.png';
            $product->setImage($file);
            file_put_contents($targetDirectory . DIRECTORY_SEPARATOR . $file, $data);
        }

        $product->setActive($request->getParsedBody()["product"]["active"]);
        $product->setCategoryId($request->getParsedBody()["product"]["category_id"]);
        $product->setCreator($this->user);
        $product->setUpdater($this->user);
        $product->insert();

        $this->redirect("/products");
    }

    /** @Post("/update") */
    public function update($request)
    {
        $data = Product::sanitize($request->getParsedBody()["product"]);

        $id = $request->getParsedBody()["product"]["id"];
        $product = Product::findOne(["id" => $id]);

        if (!empty($request->getParsedBody()["product_image"])) {
            $targetDirectory = \realpath(__DIR__ . "/../../public/images/uploads/");
            $img = $request->getParsedBody()["product_image"];
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = md5(uniqid()) .'.png';
            $product->setImage($file);
            file_put_contents($targetDirectory . DIRECTORY_SEPARATOR . $file, $data);
        }

        $product->setActive($request->getParsedBody()["product"]["active"]);
        
        $product->setCategoryId($request->getParsedBody()["product"]["category_id"]);
        $product->fromAssociativeArray($data);
        $product->setUpdater($this->user);

        $product->update();
        $this->redirect("/products");
    }

    /** @Delete("/delete/{id}") */
    public function delete($request, $id)
    {
        $product = Product::findOne(["id" => $id]);
        $product->delete();
        return "Produto #{$id} deletado com sucesso";
    }
}
