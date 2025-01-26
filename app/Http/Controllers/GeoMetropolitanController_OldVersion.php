<?php

namespace Mcms\Controllers;

use Phalcon\Mvc\Model\Criteria,
    Phalcon\Paginator\Adapter\Model as Paginator;

use Mcms\Models\GeoMetropolitan;

class GeoMetropolitanController extends ControllerBase
{
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for GeoMetropolitan
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "GeoMetropolitan", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $GeoMetropolitan = GeoMetropolitan::find($parameters);
        if (count($GeoMetropolitan) == 0) {
            $this->flash->notice("The search did not find any GeoMetropolitan");
            return $this->dispatcher->forward(array(
                "controller" => "GeoMetropolitan",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $GeoMetropolitan,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displayes the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a GeoMetropolitan
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $GeoMetropolitan = GeoMetropolitan::findFirstByid($id);
            if (!$GeoMetropolitan) {
                $this->flash->error("GeoMetropolitan was not found");
                return $this->dispatcher->forward(array(
                    "controller" => "GeoMetropolitan",
                    "action" => "index"
                ));
            }

            $this->view->id = $GeoMetropolitan->id;

            $this->tag->setDefault("id", $GeoMetropolitan->id);
            $this->tag->setDefault("title", $GeoMetropolitan->title);
            $this->tag->setDefault("officer_deg", $GeoMetropolitan->officer_deg);
            $this->tag->setDefault("address", $GeoMetropolitan->address);
            $this->tag->setDefault("created_by", $GeoMetropolitan->created_by);
            $this->tag->setDefault("created_date", $GeoMetropolitan->created_date);
            $this->tag->setDefault("update_by", $GeoMetropolitan->update_by);
            $this->tag->setDefault("update_date", $GeoMetropolitan->update_date);
            $this->tag->setDefault("delete_status", $GeoMetropolitan->delete_status);
            
        }
    }

    /**
     * Creates a new GeoMetropolitan
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "GeoMetropolitan",
                "action" => "index"
            ));
        }

        $GeoMetropolitan = new GeoMetropolitan();

        $GeoMetropolitan->id = $this->request->getPost("id");
        $GeoMetropolitan->title = $this->request->getPost("title");
        $GeoMetropolitan->officer_deg = $this->request->getPost("officer_deg");
        $GeoMetropolitan->address = $this->request->getPost("address");
        $GeoMetropolitan->created_by = $this->request->getPost("created_by");
        $GeoMetropolitan->created_date = $this->request->getPost("created_date");
        $GeoMetropolitan->update_by = $this->request->getPost("update_by");
        $GeoMetropolitan->update_date = $this->request->getPost("update_date");
        $GeoMetropolitan->delete_status = $this->request->getPost("delete_status");
        

        if (!$GeoMetropolitan->save()) {
            foreach ($GeoMetropolitan->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "GeoMetropolitan",
                "action" => "new"
            ));
        }

        $this->flash->success("GeoMetropolitan was created successfully");
        return $this->dispatcher->forward(array(
            "controller" => "GeoMetropolitan",
            "action" => "index"
        ));

    }

    /**
     * Saves a GeoMetropolitan edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "GeoMetropolitan",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $GeoMetropolitan = GeoMetropolitan::findFirstByid($id);
        if (!$GeoMetropolitan) {
            $this->flash->error("GeoMetropolitan does not exist " . $id);
            return $this->dispatcher->forward(array(
                "controller" => "GeoMetropolitan",
                "action" => "index"
            ));
        }

        $GeoMetropolitan->id = $this->request->getPost("id");
        $GeoMetropolitan->title = $this->request->getPost("title");
        $GeoMetropolitan->officer_deg = $this->request->getPost("officer_deg");
        $GeoMetropolitan->address = $this->request->getPost("address");
        $GeoMetropolitan->created_by = $this->request->getPost("created_by");
        $GeoMetropolitan->created_date = $this->request->getPost("created_date");
        $GeoMetropolitan->update_by = $this->request->getPost("update_by");
        $GeoMetropolitan->update_date = $this->request->getPost("update_date");
        $GeoMetropolitan->delete_status = $this->request->getPost("delete_status");
        

        if (!$GeoMetropolitan->save()) {

            foreach ($GeoMetropolitan->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "GeoMetropolitan",
                "action" => "edit",
                "params" => array($GeoMetropolitan->id)
            ));
        }

        $this->flash->success("GeoMetropolitan was updated successfully");
        return $this->dispatcher->forward(array(
            "controller" => "GeoMetropolitan",
            "action" => "index"
        ));

    }

    /**
     * Deletes a GeoMetropolitan
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $GeoMetropolitan = GeoMetropolitan::findFirstByid($id);
        if (!$GeoMetropolitan) {
            $this->flash->error("GeoMetropolitan was not found");
            return $this->dispatcher->forward(array(
                "controller" => "GeoMetropolitan",
                "action" => "index"
            ));
        }

        if (!$GeoMetropolitan->delete()) {

            foreach ($GeoMetropolitan->getMessages() as $message){
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "GeoMetropolitan",
                "action" => "search"
            ));
        }

        $this->flash->success("GeoMetropolitan was deleted successfully");
        return $this->dispatcher->forward(array(
            "controller" => "GeoMetropolitan",
            "action" => "index"
        ));
    }

    public function  getmetropolitanAction()
    {



        $this->view->disable();
        $childs = array();
        $city_corp = true;
        if (($this->request->isPost()) && ($this->request->isAjax() == true)) {
            $zillaid = $this->request->getQuery("ld", "string");



            $tmp = array();
            if ($zillaid) {
                $tmp = GeoMetropolitan::find("district_bbs_code=" . $zillaid);
            }



            foreach ($tmp as $t) {
                    $childs[] = array('id' => $t->id, 'name' => $t->metropolitan_name_bng ,'title' =>"");

            }

        }



        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'UTF-8');
        $response->setContent(json_encode($childs));
        return $response;
    }


}
