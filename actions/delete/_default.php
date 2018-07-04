<?php
if ($this->data->table_exists($what)) {
    if ($what=="documents") {
        $objects=$this->db->GetVal("select count(*) from docs2obj where doc_id=$id and ref_table!='partners'")*1;
        if ($objects>0) {
            $names=$this->data->get_list_csv("select ref_table||':'||ref_id from docs2obj where doc_id=$id and ref_table!='partners'");
            $name=$this->data->detalize('documents', $id);
            //$objectslists=$this->utils->F_tostring($this->db->GetResults("select filename from uploads where refid=$id and tablename='documents' and active='1'"),1);
            $this->html->error("$name can not be deleted, because it has $objects obects attached.<br>$names");
            exit;
        }
        $uploads=$this->db->GetVal("select count(*) from uploads where refid=$id and tablename='documents' and active='1'")*1;
        if ($uploads>0) {
            $name=$this->data->detalize('documents', $id);
            $uploadlists=$this->utils->F_tostring($this->db->GetResults("select filename from uploads where refid=$id and tablename='documents' and active='1'"), 1);
            $this->html->error("$name can not be deleted, because it has $uploads files attached ($uploadlists).<br>");
            exit;
        }
        

        $this->db->GetVal("delete from docs2obj where doc_id=$id");
    }

    if ($what=="events") {
        $event=$this->db->GetRow("select * from events where id=$id");
        if (!(($event[creator]==$GLOBALS[uid])||($event[executor]==$GLOBALS[uid])||($access['main_admin']))) {
            $this->html->error("<br><b>No Permission<br>Event is not created by you.</b>");
        }
        $this->db->GetRow("select * from deleteevent($id);");
    }
}
