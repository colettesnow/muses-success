<h1>Add/Remove Tags for <?php echo $listing['title']; ?></h1>

<p><strong>Tagging:</strong></p>

<ul>
    <li>Don't forget to push the "Save Tags" button to make your changes permanent.</li>
    <li>Some tag information is cached, it can take up to an hour for your changes to appear everywhere.</li>
</ul>

<div id="forums">

<table cellspacing="0" class="current_tags">
        <tr class="heading2">
                <th>Tag Name</th>
        </tr>
        <?php if (count($tags) >= 1) { foreach ($tags as $tag) { ?>
        <tr class="content">
                <td><a href="<?php echo site_url('tags/'.$tag['slug']); ?>"><?php  echo $tag['term']; ?></a></td>
        </tr>
        <?php } } ?>
</table>

</div>
<script type="text/javascript">

function string_to_slug(str) {
          str = str.replace(/^\s+|\s+$/g, ''); // trim
          
          // remove accents, swap Ã± for n, etc
          var from = "Ã€Ã�Ã„Ã‚ÃˆÃ‰Ã‹ÃŠÃŒÃ�Ã�ÃŽÃ’Ã“Ã–Ã”Ã™ÃšÃœÃ›Ã Ã¡Ã¤Ã¢Ã¨Ã©Ã«ÃªÃ¬Ã­Ã¯Ã®Ã²Ã³Ã¶Ã´Ã¹ÃºÃ¼Ã»Ã‘Ã±Ã‡Ã§Â·/_,:;";
          var to   = "aaaaeeeeiiiioooouuuuaaaaeeeeiiiioooouuuunncc------";
          for (var i=0, l=from.length ; i<l ; i++) {
            str = str.replace(new RegExp(from[i], "g"), to[i]);
          }

          str = str.replace(/[^a-zA-Z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .toLowerCase();
          return str;
        }

$(function() {
        $("#tags_adder").autocomplete("/api/fetch_tags", { mustMatch: true, minChars: 3, autoFill: true });

        $("#addtag").click(function()
        {
                var tag = $("#tags_adder").val();
                var tag_slug = string_to_slug(tag);
                var cur_tags = $("#new_tags_list").val();
                if (cur_tags == "")
                {
                 $("#new_tags_list").val(tag);                
                } else {
                 $("#new_tags_list").val(cur_tags + ", " + tag);
                }
                $(".current_tags").append("<tr class=\"content\"><td><a href=\"<?php echo site_url('/'); ?>tags/" + tag_slug + "\">" + tag + "</a></td></tr>");
        });

});


</script>

<form method="post">
        <p>
                <input type="text" name="new_tag" id="tags_adder" autocomplete="off" /> 
                <script type="text/javascript">document.write("<input type=\"button\" name=\"add_tag\" value=\"Add Tag\" id=\"addtag\" />");</script>
                <noscript><input type="submit" name="add_tags" value="Add Tag" /></noscript>
        </p>
</form>

<form method="post" class="save_tags">
        <input type="hidden" name="new_tags" id="new_tags_list" value="" />
        <p><input type="submit" name="save" value="Save Tags" /></p>
</form>

<p>Check the <a href="<?php echo site_url('tags'); ?>">tag list</a> to browse all available tags.<br />
Can't find what you're looking for? <a href="<?php echo site_url('contribute/add_new_tag'); ?>">Request a new tag.</a></p>
