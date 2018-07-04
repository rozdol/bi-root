<?php
    $id=$this->html->readRQn('id');
    $name=$this->html->readRQ('name');
    $alias=$this->html->readRQ('alias');
    $descr=$this->html->readRQ('descr');
    $addinfo=$this->html->readRQ('addinfo');
    $count=$this->db->GetVal("select id from $what where id=$id")*1;
    $vals=array(
        'id'=>$id,
        'name'=>$name,
        'alias'=>$alias,
        'descr'=>$descr,
        'addinfo'=>$addinfo
        );
    if ($count==0) {
        $id=$this->db->insert_db($what, $vals);
    } else {
        $id=$this->db->update_db($what, $id, $vals);
    }
    if ($alias=='') {
        $this->utils->post_error("Please fill the alias");
    }
    $count=$this->db->getval("SELECT count(*) from $what where upper(alias)=upper('$alias')");
    if ($count>1) {
        $this->utils->post_error("Alias must be unique");
    }
    $body.=$out;
