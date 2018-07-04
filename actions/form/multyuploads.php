<?php
if ($what == 'multyuploads') {
        $GLOBALS[fileupload]=1;
        $reftype=($this->html->readRQ('reftype'))*1;
        $refid=($this->html->readRQ('refid'))*1;
        $tablename=($this->html->readRQ('tablename'));
    if ($tablename=="documents") {
        $row=$this->db->GetRow("select * from documents where id=$refid");
        $fdate=$this->dates->F_YMDate($row[datefrom]);
        $ftype=$this->db->GetVal("select name from listitems where id=$row[type]");
            
        //if($this->data->table_exists('entities'))$partnersnamelist=$this->utils->F_tostring($this->db->GetResults("select substr(p.name,0,7) as name from entities p, docs2partners d where d.docid=$refid and d.partnerid=p.id"));
        //if($this->data->table_exists('partners'))$partnersnamelist=$this->utils->F_tostring($this->db->GetResults("select substr(p.name,0,7) as name from partners p, docs2partners d where d.docid=$refid and d.partnerid=p.id"));
        $partnersnamelist=substr($partnersnamelist, 0, strlen($partnersnamelist)-1);
        $fsum=$row[amount];
        $newfname="$fdate-$ftype-$partnersnamelist-$fsum";
    }
    if ($act=='edit') {
        $sql="select * from $what WHERE id='$id' and userid=$uid";
        $res=$this->utils->escape($this->db->GetRow($sql));

        $descr="<label>File</label><input type='text' name='name' maxlength='255'  value='$res[name]' disabled>
				<label>Descr</label><input type='text' name='descr' size='30' maxlength='255'  value='$res[descr]'>
				<label>Tags:</label><textarea cols=60 rows=6 name='tags' >$res[tags]</textarea>
				<input type='hidden' name='id' value='$id'>
				<input type='hidden' name='refid' value='$refid'>
				<input type='hidden' name='update' value='$id'>";
    } else {
        $id='';
        $descr="<label>File</label><input name='ufile' type='file' id='ufile' class='ufile'>
				<label>Descr</label><input type='text' name='descr' size='30' maxlength='255'>
				<label>Tags:</label><textarea cols=60 rows=6 name='tags' ></textarea>";
            $descr="
					<label>Descr</label><input type='text' name='descr' size='30' maxlength='255'>
					<label>Tags:</label><textarea cols=60 rows=6 name='tags' ></textarea>";
    }
        //$sql="select * from logs where action like 'JSON%' order by id desc limit 1";
        //$row=$this->db->GetRow($sql);
        //$out.="$row[date], $row[action]<br>";
        //$sql="select * from uploads order by id desc limit 1";
        //$row=$this->db->GetRow($sql);
        //$out.="$row[id], $row[filename]<br>";
        //<form id="fileupload" action="ffupload/server/php/upload.php" method="POST" enctype="multipart/form-data">
        //<form id="fileupload" action="?act=json&what=upload" method="POST" enctype="multipart/form-data">
        $form="
			 <a href='?act=details&what=documents&id=$refid'><span class='btn btn-info'>
                    <i class='icon-arrow-left icon-white'></i>
                    <span>Back</span>
                </span></a>";
        $form.='
		<div class="container">
		    <div class="page-header">
		        <h1>File Upload to Document '.$row[name].' ID:'.$refid.'</h1>
		    </div>
		    <br>
		    <!-- The file upload form used as target for the file upload widget -->
		    <form id="fileupload" action="?act=json&what=upload&refid='.$refid.'" method="POST" enctype="multipart/form-data">
		        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
				<input type="hidden" name="refid" value="'.$refid.'">
				<input type="hidden" name="tablename" value="'.$tablename.'">
				<input type="hidden" name="reftype" value="'.$reftype.'">
				<input type="hidden" name="newfname" value="'.$newfname.'">
				'.$descr2.'
		        <div class="row fileupload-buttonbar">
		            <div class="span7">
		                <!-- The fileinput-button span is used to style the file input field as button -->
		                <span class="btn btn-success fileinput-button">
		                    <i class="icon-plus icon-white"></i>
		                    <span>Add files...</span>
		                    <input type="file" name="files[]" multiple>
		                </span>
		                <button type="submit" class="btn btn-primary start">
		                    <i class="icon-upload icon-white"></i>
		                    <span>Start upload</span>
		                </button>
		                <button type="reset" class="btn btn-warning cancel">
		                    <i class="icon-ban-circle icon-white"></i>
		                    <span>Cancel upload</span>
		                </button>
		                
		            </div>
		            <div class="span5">
		                <!-- The global progress bar -->
		                <div class="progress progress-success progress-striped active fade">
		                    <div class="bar" style="width:0%;"></div>
		                </div>
		            </div>
		        </div>
		        <!-- The loading indicator is shown during image processing -->
		        <div class="fileupload-loading"></div>
		        <br>
		        <!-- The table listing the files available for upload/download -->
		        <table class="table table-striped"><tbody class="files" data-toggle="modal-gallery" data-target="#modal-gallery"></tbody></table>
		    </form>
		    <br>
		</div>
		<!-- modal-gallery is the modal dialog used for the image gallery -->
		<div id="modal-gallery" class="modal modal-gallery hide fade">
		    <div class="modal-header">
		        <a class="close" data-dismiss="modal">&times;</a>
		        <h3 class="modal-title"></h3>
		    </div>
		    <div class="modal-body"><div class="modal-image"></div></div>
		    <div class="modal-footer">
		        <a class="btn modal-download" target="_blank">
		            <i class="icon-download"></i>
		            <span>Download</span>
		        </a>
		        <a class="btn btn-success modal-play modal-slideshow" data-slideshow="5000">
		            <i class="icon-play icon-white"></i>
		            <span>Slideshow</span>
		        </a>
		        <a class="btn btn-info modal-prev">
		            <i class="icon-arrow-left icon-white"></i>
		            <span>Previous</span>
		        </a>
		        <a class="btn btn-primary modal-next">
		            <span>Next</span>
		            <i class="icon-arrow-right icon-white"></i>
		        </a>
		    </div>
		</div>
		<!-- The template to display files available for upload -->
		<script id="template-upload" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		    <tr class="template-upload fade">
		        <td class="preview"><span class="fade"></span></td>
		        <td class="name"><span>{%=file.name%}</span></td>
		        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
		        {% if (file.error) { %}
		            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
		        {% } else if (o.files.valid && !i) { %}
		            <td>
		                <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
		            </td>
		            <td class="start">{% if (!o.options.autoUpload) { %}
		                <button class="btn btn-primary">
		                    <i class="icon-upload icon-white"></i>
		                    <span>{%=locale.fileupload.start%}</span>
		                </button>
		            {% } %}</td>
		        {% } else { %}
		            <td colspan="2"></td>
		        {% } %}
		        <td class="cancel">{% if (!i) { %}
		            <button class="btn btn-warning">
		                <i class="icon-ban-circle icon-white"></i>
		                <span>{%=locale.fileupload.cancel%}</span>
		            </button>
		        {% } %}</td>
		    </tr>
		{% } %}
		</script>
		<!-- The template to display files available for download -->
		<script id="template-download" type="text/x-tmpl">
		{% for (var i=0, file; file=o.files[i]; i++) { %}
		    <tr class="template-download fade">
		        {% if ((file.error) && (file.error!=\'SyntaxError: JSON.parse: unexpected character\')) { %}
		            <td></td>
		            <td class="name"><span>{%=file.name%}</span></td>
		            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
		            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
		        {% } else { %}
		            <td class="preview">{% if (file.thumbnail_url) { %}
		                <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
		            {% } %}</td>
		            <td class="name">
		                {%=file.name%}
		            </td>
		            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
		            <td colspan="2"></td>
		        {% } %}
		        
		    </tr>
		{% } %}
		</script>
		';

        $old= " 
			<div id='stylized' class='well'>
			<form action='?csrf=$GLOBALS[csrf]&act=save&what=uploads' method='post' name='uploads' enctype='multipart/form-data' > 
			<h1>Upload File</h1>
			<p>Large file may take longer time</p>
			<input type='hidden' name='refid' value='$refid'>
			<input type='hidden' name='tablename' value='$tablename'>
			<input type='hidden' name='reftype' value='$reftype'>
			<input type='hidden' name='newfname' value='$newfname'>
			$descr
			".$this->html->form_confirmations()."
				<button type='submit' name='act' value='save' id='button' class='btn btn-primary'  onClick='document.getElementById(\"button\").innerHTML=\"Wait...\";'>Save</button>
			<div class='spacer'></div>
			</form>
			</div>";
        $out.= "$form $form2";
}
    
$body.=$out;
