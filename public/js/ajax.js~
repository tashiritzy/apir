
function addRecord() {
	//Project name field validation
	if( document.myform.prjname.value == "" )
         {
            alert( "Please provide project name!" );
            document.myform.prjname.focus() ;
            return false;
         }
         
         var url = "prj/add";
         var datastring = $("#myform").serialize();
    
         $.ajax({
	    method: 'POST',
	    //dataType: "json",
	    url: url,
	    data: datastring,
	    //cache: false,
	    success: function(response) {
                
	    	//var obj = jQuery.parseJSON(data); if the dataType is not specified as json uncomment this
		// do what ever you want with the server response
		//alert(JSON.stringify(response, null, 4));
		
		console.log(response);
				
		// close the popup
		$("#add_new_record_modal").modal("hide");
				
		// clear fields from the popup
		$("#prjname").val("");
		$("#clientname").val("");
		$("#prjmanagername").val("");
		$("#prjstartdate").val("");
		
		// read records again
		//location.reload();
		readRecords();
		
	    },
	    error: function() {
		alert('error handing here');
		//console.log(json);
		$("#add_new_record_modal").modal("hide");
	    }
	}); 
}
 
// READ records
function readRecords() {
    $.get("prj/readrecord", {}, function (data) {
        $(".records_content").html(data);
        
    });
}

//$(document).ready(function () {
    // READ recods on page load
  //  readRecords(); // calling function
//});

//$(function(){
  // readRecords();
//});

function getDetails(id){
   
    // Add ID to the hidden field for furture usage
    $("#hidden_id").val(id);
    jQuery.ajax({
    	    method: "POST",
    	    url: "prj/readprjdetails", 
    	    data: {id: id}, 
    	    dataType: "json",
    	    cache: false,
    	    success: function (res, status) {
            
    	    //alert(id); 	    
    	   
            //alert(response);
            console.log(res);
            
            //alert(JSON.stringify(response.response.prjname))
            // PARSE json data
            //var pj = JSON.parse(response);
            //var pj = jQuery.parseJSON(JSON.stringify(response));
            
            // Placing existing values to the modal popup fields
            $("#eprjname").val(res.response.prjname);
            $("#eclientname").val(res.response.clientname);
            $("#eprjmanagername").val(res.response.prjmanagername);
            $("#eprjstartdate").val(res.response.prjstartdate);
        }
    });
    // Open modal popup
    $("#edit_modal").modal("show");
}

//save new record / update existing record
function saveRecord() {
	 // Project Name field validation
	 if( document.myeform.eprjname.value == "" )
	 {
	    alert( "Project name cannot be empty!" );
	    document.myeform.eprjname.focus() ;
	    return false;
	 }
	    
	var url = "prj/edit";
	var datastring = $("#myeform").serialize();
	
	$.ajax({
	    method: 'POST',
	    url: url,
	    data: datastring,
	    //dataType: "json",
	    //headers: {'Content-Type': 'application/x-www-form-urlencoded'},
	    success: function(response) {
		//var obj = jQuery.parseJSON(data); if the dataType is not specified as json uncomment this
		// do what ever you want with the server response
		//alert(JSON.stringify(data, null, 4));
		console.log(response);
		
		// close the popup
		$("#edit_modal").modal("hide");
		
		// read records again
		readRecords();
	    },
	    error: function() {
		alert('error handing here');
		$("#edit_modal").modal("hide");
	    }
            
      });
}

    //delete record
function deleteRecord(id) {
    var conf = confirm("Are you sure, do you really want to delete?"+id);
    if (conf == true) {
        $.post("prj/delete", {
                id: id
            },
            function (data, status) {
                // reload data view by using readRecords();
                readRecords();
            }
        );
    }
}

  
