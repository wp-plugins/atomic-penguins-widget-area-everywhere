jQuery(document).ready(function($){
//variables

  var Alert = new CustomAlert();
  var glob_id;
  var del_id;
  var edit_id;

//functions
save_wae();
get_all_wae();
remove_wae();
get_one_wae();
cancel_all();
update_wae();
do_delete();
cancel_delete();


	function save_wae()
	{
		$("#savebtn").click(function()
		{
			$name = $("#wae_name").val();
			$id = $("#wae_id").val();
			$wea_class = $("#wae_class").val();
			$desc = $("#desc").val();
			if ($name == "")
      		{
            	alert("Name and ID Required");
      		}
      		else if($id == "" || $id.indexOf(" ") >= 0)
      		{
      			alert("ID is required and please use underscores or hyphen for spaces");
      		}
      		else
      		{
      			var s = {action: "SAVE_WAE", data : {name: $name, id: $id, wea_class: $wea_class, desc: $desc}};
      			$.post(ajaxurl, s, function(t)
			    {
			   		location.reload();
			    })
      		}
		})
	}

	function get_all_wae()
	{
		var s = {action: "GET_ALL_WAE",  data: {}};
		$.post(ajaxurl, s, function(t) {
              $.each(t, function(s, n) {
              	  var num_id = n.id;
                  var name = n.wae_name;
                  var id = n.wae_id;
                  var code_id = " '" + n.wae_id + "' ";
                  var wae_class = n.wae_class;
                  var wae_desc = n.wae_desc;
                  $("#wae_holder").append('<tr id="' + num_id + '"><td>' + num_id +'</td><td>'+ name +'</td><td>'+ id +'</td><td>' + wae_class + '</td><td>' + wae_desc + '</td>  <td>[wae id="'+id+'"]</td>  <td>dynamic_sidebar( '+code_id+' )</td>  <td><button id="edit_' + num_id + '" class="edit" style="width:70px">Edit</button>       <button id="del_' + num_id + '" class="remove" style="width:70px">Delete</button></td></tr>');
              });
            
          })
	}


	function remove_wae()
	{
		$(".remove").live("click",function()
		{
			 var t = this;
			 del_id = this.id.replace("del_","")
			

			Alert.render("You are about to delete a widget area. Continue?","<button class=\"\" id=\"confirm_del_btn\" style=\"margin-right: 5	px\">Yes</button><button id=\"cancel_del_btn\">No</button>");
		})
	}

	function do_delete()
	{
		$("#confirm_del_btn").live("click",function()
		{
			var q = {action: "DELETE", id: del_id};
			$.post(ajaxurl, q, function () 
			{
                $("tr#"+del_id).remove();
                if (del_id == edit_id)
                {
                	location.reload();
                }
                else
                {
                	Alert.ok();
                }
                
            })
			//alert(del_id);
		})
	}

	function cancel_delete()
	{
		$("#cancel_del_btn").live("click",function()
		{
			Alert.ok();
		})
	}

	function get_one_wae()
	{
		$(".edit").live("click",function()
		{
			var t = this;
			var id = this.id.replace("edit_","");
			glob_id = id;
			edit_id = id;
			// alert(id);
			var q = {action: 'GET_ONE_WAE',id: id};
			$.post(ajaxurl, q, function(t) {
              $.each(t, function(q, n) {
              	  var num_id = n.id;
              	  //alert(num_id);
              	  var name = n.wae_name;
              	  var id = n.wae_id;
              	  var wae_class = n.wae_class;
              	  var desc = n.wae_desc;
              	  $("#wae_name").val(name);
              	  $("#wae_id").val(id);
              	  $("#wae_class").val(wae_class);
              	  $("#desc").val(desc);
              	  $("#updatebtn").css("display","block");
              	  $("#savebtn").css("display","none");
              });
            
          })
		})
	}

	function cancel_all()
	{
		$("#cancelbtn").click(function()
		{
			location.reload();
		})
		
	}

	function update_wae()
	{
		$("#updatebtn").click(function()
		{
			$name = $("#wae_name").val();
			$id = $("#wae_id").val();
			$wea_class = $("#wae_class").val();
			$desc = $("#desc").val();

			if ($name == "")
      		{
            	alert("Name and ID Required");
      		}
      		else if($id == "" || $id.indexOf(" ") >= 0)
      		{
      			alert("ID is required and please use underscores or hyphen for spaces");
      		}
      		else
      		{
      			var s = {action: "UPDATE_WAE", data : {name: $name, id: $id, wea_class: $wea_class, desc: $desc}, id : glob_id};
      			$.post(ajaxurl, s, function(t)
			    {
			   		location.reload();
			   		glob_id = "";
			    })
      		}
		})
	}




	//function for the alert box
	function CustomAlert(){
        this.render = function(dialog,confirm){
            var winW = window.innerWidth;
            var winH = window.innerHeight;
            var dialogoverlay = document.getElementById('dialogoverlay');
            var dialogbox = document.getElementById('dialogbox');
            dialogoverlay.style.display = "block";
            dialogoverlay.style.height = winH+"px";
            dialogbox.style.left = (winW/2) - (550 * .5)+"px";
            dialogbox.style.top = "100px";
            dialogbox.style.display = "block";
            document.getElementById('dialogboxhead').innerHTML = "HEY THERE!";
            document.getElementById('dialogboxbody').innerHTML = dialog;
            document.getElementById('dialogboxfoot').innerHTML = confirm;
        }

        this.ok = function(){
            document.getElementById('dialogbox').style.display = "none";
            document.getElementById('dialogoverlay').style.display = "none";
        }
  }

   // $(function(){
   //    // $(".card").flip();
      
   //    $("#card-1").flip({
   //      axis: "y", // y or x
   //      reverse: false, // true and false
   //      trigger: "click", // click or hover
   //      speed: 1000
   //    });
   //    $("#card-2").flip({
   //      axis: "x",
   //      reverse: true,
   //      trigger: "click"
   //    });
   //  });
})