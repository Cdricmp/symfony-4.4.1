// $(document).ready(function(){
//     console.log("OK");

//     $(document).on('click', 'button.ajax', function(){

//         $.ajax({
//             url:        '/post/api/post',
//             type:       'GET',
//             dataType:   'json',
//             async:      true,

//                 success: function(data, status) {
//                 console.log(data)
//                 console.log(status)

//             for (var item in data) {
                
//                 var e = $('<tr><th>Title</th><th>Summary</th><th>Created_at</th><th>Author</th><th>Image</th><th>Action</th></tr>');
//                 $('#post').html('');
//                 $('#post').append(e);

//             for(i=0; i< data[item].length; i++){
//                 var post = data[item][i];
//                 console.log(data[item]);
//                 console.log(data[item][i]);

//                 var e = $('<tr><td id="title"></td><td id="summary"></td><td id="created_at"></td><td id="author"></td><td id="image"></td><td id="action"></td></tr>');
//                 $('#title', e).html(post['title']);
//                 $('#summary', e).html(post['summary']);
//                 $('#created_at', e).html(post['created_at']['date']);
//                 $('#author', e).html(post['username']);
//                 $('#image', e).html(post['image']);
//                 $('#action').html(post['action']);
//             }
//             }
//             }
//         })
//     })
// })


$(document).ready(function() {

    $(document).on('click', 'button.ajax', function(){
                
                $.ajax({  
                url:        'api/post',  
                type:       'GET',   
                dataType:   'json',  
                async:      true,  
                
                success: function(data, status) { 
                console.log(data)  
                console.log(status)
 
             for (var item in data) {
 
               var e = $('<tr><th>Title</th><th>Summary</th><th>Created_at</th><th>Author</th><th>Image</th></tr>');  
               $('#post').html('');  
               $('#post').append(e);
 
               for (i=0; i < data[item].length; i++){
                  var post = data[item][i];
                  console.log(data[item]);
                  console.log(data[item][i]);
 
 
                  var e = $('<tr><td id = "title"></td><td id = "summary"></td><td id = "created_at"></td><td id = "author"></td><td id = "image"></td></tr>');
                 
                 $('#title', e).html(post['title']);  
                 $('#summary', e).html(post['summary']);
                 $('#created_at', e).html(post['created_at']['date']);
                 $('#author', e).html(post['username']);
 
                  var monImg = document.createElement('img');
                  monImg.src="/"+post['image'];
 
                 $('#image', e).html(monImg);
                 $('#image img').css({"width":"75px","max-height":"75px"});
 
                 $('#post').append(e);
               }
             }
             },  
                error : function(xhr, textStatus, errorThrown) {  
                   alert('Ajax request failed.');  
                }  
             });  
          });  
 
 });