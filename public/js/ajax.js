function addRecord() {
    // get values
    //var prjname = $("#prjname").val();
    //var clientname = $("#clientname").val();
 
    var datastring = $("#myform").serialize();
    
    alert(datastring);
	jQuery.ajax({
	    type: "POST",
	    dataType: "json",
	    url: "prj/add",
	    data: datastring,
	    cache: false,
	    //contentType: {'application/json; charset=utf-8'},
	    //headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    success: function(json) {
                console.log(json.prjname + ' ' + json.clientname);
		
                jQuery('.records_content').html(json);
                //var obj = jQuery.parseJSON(data); if the dataType is not specified as json uncomment this
		// do what ever you want with the server response
		alert(JSON.stringify(data, null, 4));
		//console.log(data);
		
		// close the popup
		$("#add_new_record_modal").modal("hide");
		
		// read records again
		//readRecords();
	    },
	    error: function() {
		alert('error handing here');
		$("#add_new_record_modal").modal("hide");
	    }
	});
   
	/*
	    // Add record
	    
	    $.post("prj/add", {
		prjname: prjname,
		clientname: clientname
	    }, function (response, status) {
		    
		    console.log(response);
		    
		// close the popup
		$("#add_new_record_modal").modal("hide");
	 
		// read records again
		readRecords();
	 
		// clear fields from the popup
		$("#prjname").val("");
		$("#clientname").val("");
	   
	    }); 
	  */  
}
 
// READ records
function readRecords() {
    $.get("prj/index", {}, function (data) {
        $(".records_content").html(data);
    });
}


//save new record / update existing record
    function save(id) {
    	    
        var url = "prj/edit";
        var datastring = $("#myform").serialize();
        
        $.ajax({
            method: 'POST',
            url: url,
            data: $datastring,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            success: function(data) {
                //console.log(data.prjname + ' ' + data.clientname);
		
                //var obj = jQuery.parseJSON(data); if the dataType is not specified as json uncomment this
		// do what ever you want with the server response
		alert(JSON.stringify(data, null, 4));
		console.log(data);
		
		// close the popup
		$("#editmodal").modal("hide");
		
		// read records again
		readRecords();
	    },
	    error: function() {
		alert('error handing here');
		$("#add_new_record_modal").modal("hide");
	    }
            
        });
    }

    //delete record
    function confirmDelete(id) {
        var isConfirmDelete = confirm('Are you sure you want this record?');
        if (isConfirmDelete) {
            $.ajax({
                method: 'DELETE',
                url: 'prj/delete',
                success: function(data) {
                
		console.log(data);
		
		// close the popup
		$("#deletemodal").modal("hide");
		
		// read records again
		location.reload();
	    },
	    error: function() {
		alert('error handing here');
		$("#add_new_record_modal").modal("hide");
	    	}         
	    });
	 }
     }
   
  
