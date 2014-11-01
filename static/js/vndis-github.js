/**
 * Created by honganh on 10/31/2014.
 */
jQuery(document).ready(function(){
    jQuery('#vndis-github-search').submit(function(e){
        var keyword = jQuery('#vndis-github-search-keyword').val();
        var language = jQuery('#vndis-github-search-language').val();
        var page = jQuery('#vndis-github-search-page').val();
        jQuery.ajax({
            url:'http://wordpress.dev/wp-admin/admin-ajax.php',
            data:{action:'github_search', keyword:keyword, language: language, page:page},
            success:function(data){
                console.log(data);
                if(data){
                    data = JSON.parse(data);
                }
            }
        });
        e.preventDefault();
    });
});
