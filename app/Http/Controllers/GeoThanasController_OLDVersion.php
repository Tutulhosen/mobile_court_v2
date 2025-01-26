<?php

namespace Mcms\Controllers;

use Phalcon\Mvc\Model\Criteria,
    Phalcon\Paginator\Adapter\Model as Paginator;

use Mcms\Models\GeoThanas;

class GeoThanasController extends ControllerBase
{

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for GeoThanas
     */
    public function searchAction()
    {

        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "GeoThanas", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $GeoThanas = GeoThanas::find($parameters);
        if (count($GeoThanas) == 0) {
            $this->flash->notice("The search did not find any GeoThanas");
            return $this->dispatcher->forward(array(
                "controller" => "GeoThanas",
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $GeoThanas,
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
     * Edits a GeoThanas
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $GeoThanas = GeoThanas::findFirstByid($id);
            if (!$GeoThanas) {
                $this->flash->error("GeoThanas was not found");
                return $this->dispatcher->forward(array(
                    "controller" => "GeoThanas",
                    "action" => "index"
                ));
            }

            $this->view->id = $GeoThanas->id;

            $this->tag->setDefault("id", $GeoThanas->id);
            $this->tag->setDefault("title", $GeoThanas->title);
            $this->tag->setDefault("officer_deg", $GeoThanas->officer_deg);
            $this->tag->setDefault("address", $GeoThanas->address);
            $this->tag->setDefault("created_by", $GeoThanas->created_by);
            $this->tag->setDefault("created_date", $GeoThanas->created_date);
            $this->tag->setDefault("update_by", $GeoThanas->update_by);
            $this->tag->setDefault("update_date", $GeoThanas->update_date);
            $this->tag->setDefault("delete_status", $GeoThanas->delete_status);
            
        }
    }

    /**
     * Creates a new GeoThanas
     */
    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "GeoThanas",
                "action" => "index"
            ));
        }

        $GeoThanas = new GeoThanas();

        $GeoThanas->id = $this->request->getPost("id");
        $GeoThanas->title = $this->request->getPost("title");
        $GeoThanas->officer_deg = $this->request->getPost("officer_deg");
        $GeoThanas->address = $this->request->getPost("address");
        $GeoThanas->created_by = $this->request->getPost("created_by");
        $GeoThanas->created_date = $this->request->getPost("created_date");
        $GeoThanas->update_by = $this->request->getPost("update_by");
        $GeoThanas->update_date = $this->request->getPost("update_date");
        $GeoThanas->delete_status = $this->request->getPost("delete_status");
        

        if (!$GeoThanas->save()) {
            foreach ($GeoThanas->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(array(
                "controller" => "GeoThanas",
                "action" => "new"
            ));
        }

        $this->flash->success("GeoThanas was created successfully");
        return $this->dispatcher->forward(array(
            "controller" => "GeoThanas",
            "action" => "index"
        ));

    }

    /**
     * Saves a GeoThanas edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "controller" => "GeoThanas",
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $GeoThanas = GeoThanas::findFirstByid($id);
        if (!$GeoThanas) {
            $this->flash->error("GeoThanas does not exist " . $id);
            return $this->dispatcher->forward(array(
                "controller" => "GeoThanas",
                "action" => "index"
            ));
        }

        $GeoThanas->id = $this->request->getPost("id");
        $GeoThanas->title = $this->request->getPost("title");
        $GeoThanas->officer_deg = $this->request->getPost("officer_deg");
        $GeoThanas->address = $this->request->getPost("address");
        $GeoThanas->created_by = $this->request->getPost("created_by");
        $GeoThanas->created_date = $this->request->getPost("created_date");
        $GeoThanas->update_by = $this->request->getPost("update_by");
        $GeoThanas->update_date = $this->request->getPost("update_date");
        $GeoThanas->delete_status = $this->request->getPost("delete_status");
        

        if (!$GeoThanas->save()) {

            foreach ($GeoThanas->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "GeoThanas",
                "action" => "edit",
                "params" => array($GeoThanas->id)
            ));
        }

        $this->flash->success("GeoThanas was updated successfully");
        return $this->dispatcher->forward(array(
            "controller" => "GeoThanas",
            "action" => "index"
        ));

    }

    /**
     * Deletes a GeoThanas
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $GeoThanas = GeoThanas::findFirstByid($id);
        if (!$GeoThanas) {
            $this->flash->error("GeoThanas was not found");
            return $this->dispatcher->forward(array(
                "controller" => "GeoThanas",
                "action" => "index"
            ));
        }

        if (!$GeoThanas->delete()) {

            foreach ($GeoThanas->getMessages() as $message){
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => "GeoThanas",
                "action" => "search"
            ));
        }

        $this->flash->success("GeoThanas was deleted successfully");
        return $this->dispatcher->forward(array(
            "controller" => "GeoThanas",
            "action" => "index"
        ));
    }

    public function  getthanasAction()
    {
        $this->view->disable();
        $childs = array();
        $city_corp = true;
        if (($this->request->isPost()) && ($this->request->isAjax() == true)) {
            $metropolitanid = $this->request->getQuery("ld", "string");



            $tmp = array();
            if ($metropolitanid) {
                $tmp = GeoThanas::find("geo_metropolitan_id=" . $metropolitanid);
            }



            foreach ($tmp as $t) {
                    $childs[] = array('id' => $t->id, 'name' => $t->thana_name_bng ,'title' =>"");

            }

        }
        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'UTF-8');
        $response->setContent(json_encode($childs));
        return $response;
    }

    public function getThanaByUsersZillaIdAction(){
        $this->view->disable();
        $thanaArray = array();
        $user = $this->auth->getUserLocation();

        $tmp = array();
        if ($user['zillaid']) {
            $tmp = GeoThanas::find("district_bbs_code=" .$user['zillaid'] );
        }

        foreach ($tmp as $t) {
            $thanaArray[] = array('id' => $t->id, 'name' => $t->thana_name_bng );

        }

        $response = new \Phalcon\Http\Response();
        $response->setContentType('application/json', 'UTF-8');
        $response->setContent(json_encode($thanaArray));
        return $response;
    }


}
