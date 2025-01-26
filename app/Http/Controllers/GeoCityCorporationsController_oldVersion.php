<?php

namespace Mcms\Controllers;

use Phalcon\Mvc\Model\Criteria,
    Phalcon\Paginator\Adapter\Model as Paginator;

use Mcms\Models\GeoCityCorporations;

class GeoCityCorporationsController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for GeoCityCorporations
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "GeoCityCorporations", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $GeoCityCorporations = GeoCityCorporations::find($parameters);
        if (count($GeoCityCorporations) == 0) {
            $this->flash->notice("The search did not find any GeoCityCorporations");
            return $this->dispatcher->forward(array(
                "controller" => "GeoCityCorporations",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $GeoCityCorporations,
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
     * Edits a GeoCityCorporations
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $GeoCityCorporations = GeoCityCorporations::findFirstByid($id);
            if (!$GeoCityCorporations) {
                $this->flash->error("GeoCityCorporations was not found");
                return $this->dispatcher->forward(array(
                    "controller" => "GeoCityCorporations",
                    "action" => "index"
                ));
            }

            $this->view->id = $GeoCityCorporations->id;

            $this->tag->setDefault("id", $GeoCityCorporations->id);
            $this->tag->setDefault("title", $GeoCityCorporations->title);
            $this->tag->setDefault("officer_deg", $GeoCityCorporations->officer_deg);
            $this->tag->setDefault("address", $GeoCityCorporations->address);
            $this->tag->setDefault("created_by", $GeoCityCorporations->created_by);
            $this->tag->setDefault("created_date", $GeoCityCorporations->created_date);
            $this->tag->setDefault("update_by", $GeoCityCorporations->update_by);
            $this->tag->setDefault("update_date", $GeoCityCorporations->update_date);
            $this->tag->setDefault("delete_status", $GeoCityCorporations->delete_status);
            
        }
    }

    /**
     * Creates a new GeoCityCorporations
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "GeoCityCorporations",
                "action" => "index"
            ));
        }

        $GeoCityCorporations = new GeoCityCorporations();

        $GeoCityCorporations->id = $this->request->getPost("id");
        $GeoCityCorporations->title = $this->request->getPost("title");
        $GeoCityCorporations->officer_deg = $this->request->getPost("officer_deg");
        $GeoCityCorporations->address = $this->request->getPost("address");
        $GeoCityCorporations->created_by = $this->request->getPost("created_by");
        $GeoCityCorporations->created_date = $this->request->getPost("created_date");
        $GeoCityCorporations->update_by = $this->request->getPost("update_by");
        $GeoCityCorporations->update_date = $this->request->getPost("update_date");
        $GeoCityCorporations->delete_status = $this->request->getPost("delete_status");
        

        if (!$GeoCityCorporations->save()) {
            foreach ($GeoCityCorporations->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "GeoCityCorporations",
                "action" => "new"
            ));
        }

        $this->flash->success("GeoCityCorporations was created successfully");
        return $this->dispatcher->forward(array(
            "controller" => "GeoCityCorporations",
            "action" => "index"
        ));

    }

    /**
     * Saves a GeoCityCorporations edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "GeoCityCorporations",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $GeoCityCorporations = GeoCityCorporations::findFirstByid($id);
        if (!$GeoCityCorporations) {
            $this->flash->error("GeoCityCorporations does not exist " . $id);
            return $this->dispatcher->forward(array(
                "controller" => "GeoCityCorporations",
                "action" => "index"
            ));
        }

        $GeoCityCorporations->id = $this->request->getPost("id");
        $GeoCityCorporations->title = $this->request->getPost("title");
        $GeoCityCorporations->officer_deg = $this->request->getPost("officer_deg");
        $GeoCityCorporations->address = $this->request->getPost("address");
        $GeoCityCorporations->created_by = $this->request->getPost("created_by");
        $GeoCityCorporations->created_date = $this->request->getPost("created_date");
        $GeoCityCorporations->update_by = $this->request->getPost("update_by");
        $GeoCityCorporations->update_date = $this->request->getPost("update_date");
        $GeoCityCorporations->delete_status = $this->request->getPost("delete_status");
        

        if (!$GeoCityCorporations->save()) {

            foreach ($GeoCityCorporations->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "GeoCityCorporations",
                "action" => "edit",
                "params" => array($GeoCityCorporations->id)
            ));
        }

        $this->flash->success("GeoCityCorporations was updated successfully");
        return $this->dispatcher->forward(array(
            "controller" => "GeoCityCorporations",
            "action" => "index"
        ));

    }

    /**
     * Deletes a GeoCityCorporations
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $GeoCityCorporations = GeoCityCorporations::findFirstByid($id);
        if (!$GeoCityCorporations) {
            $this->flash->error("GeoCityCorporations was not found");
            return $this->dispatcher->forward(array(
                "controller" => "GeoCityCorporations",
                "action" => "index"
            ));
        }

        if (!$GeoCityCorporations->delete()) {

            foreach ($GeoCityCorporations->getMessages() as $message){
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "GeoCityCorporations",
                "action" => "search"
            ));
        }

        $this->flash->success("GeoCityCorporations was deleted successfully");
        return $this->dispatcher->forward(array(
            "controller" => "GeoCityCorporations",
            "action" => "index"
        ));
    }

    public function  getCityCorporationAction()
    {
        $this->view->disable();
        $childs = array();
        $city_corp = true;
        if (($this->request->isPost()) && ($this->request->isAjax() == true)) {
            $zillaid = $this->request->getQuery("ld", "string");



            $tmp = array();
            if ($zillaid) {
                $tmp = GeoCityCorporations::find("district_bbs_code=" . $zillaid);
            }



            foreach ($tmp as $t) {
                    $childs[] = array('id' => $t->id, 'name' => $t->city_corporation_name_bng ,'title' =>"");

            }

        }
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'UTF-8');
        $response->setContent(json_encode($childs));
        return $response;
    }


}
