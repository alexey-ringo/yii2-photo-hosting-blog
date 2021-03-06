jQuery(document).ready(function ($) {


    /*====== AJAX methods for create and realtime add comments on the Post page view.php ======*/
    
    $('#main-comment-btn').on('click', function(event) {
        event.preventDefault();
        var comment = $('#main-comment').val();
        var id = $('#main-comment').attr('data-id');
        if(!comment) {
            return false;
        }
		$.ajax({
 			url: '/post/default/create-comment',
 			data: {
 				text: comment,
 				post_id: id,
 			},
 			type: 'POST',
 			success: function(res) {
 				if(!res) {
 					alert('Ошибка связи с сервером!');
 				}
 				/*
 				var result = $.parseJSON(res);
 				if(!result.success) {
 					alert('Ошибка добавления комментария!');
 				}
 				else {
 				//    if(result.comments.html) $('.comment-list').html(result.comments.html);
 				 $('.comment-list').html(result.comments);
 				}
 				*/
 				$('.comment-list').html(res);
 				$('#main-comment').val('');
 			},
 			error: function() {
 				alert('Error!');
 			}
 		});
        return false;
    });

    
});