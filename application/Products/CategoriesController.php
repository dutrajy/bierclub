<?php

namespace Commercial\Application\Products;

/** @Controller("/categories") */
class CategoriesController extends \Commercial\Application\Common\Controller
{
    /** @Any */
    public function list()
    {
        return $this->render(
            "Categories/List",
            [
                "categories" => Category::findAll()
            ]
        );
    }

    /** @Any("/new") */
    public function new()
    {
        return $this->render(
            "Categories/Form",
            [
                "action" => "create",
                "title" => "Nova Categoria",
                "category" => new Category(),
            ]
        );
    }

    /** @Post("/create") */
    public function create($request)
    {
        $category = new Category($request->getParsedBody()["category"]);

        $category->setCreator($this->user);
        $category->setUpdater($this->user);
        $category->insert();

        $this->redirect("/categories");
    }
}