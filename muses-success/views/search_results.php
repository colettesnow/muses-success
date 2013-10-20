<?php

// Original <em>PHP</em> code by Chirp Internet: <a class="linkification-ext" href="http://www.chirp.com.au" title="Linkification: http://www.chirp.com.au">www.chirp.com.au</a>
// Please acknowledge use of this code by including this header.
function myTruncate($string, $limit, $break=" ", $pad="...")
  {

    if(strlen($string) <= $limit) return $string;

    $string = substr($string, 0, $limit);
    if(false !== ($breakpoint = strrpos($string, $break))) {
      $string = substr($string, 0, $breakpoint);
    }

    return $string . $pad;
  }
  
function restoreTags($input)
{
  $opened = $closed = array();

  if(preg_match_all("/<(\/?[a-z]+)>/i", $input, $matches)) {
    foreach($matches[1] as $tag) {
      if(preg_match("/^[a-z]+$/i", $tag, $regs)) {
        $opened[] = $regs[0];
      } elseif(preg_match("/^\/([a-z]+)$/i", $tag, $regs)) {
        $closed[] = $regs[1];
      }
    }
  }

  if($closed) {
    foreach($opened as $idx => $tag) {
      foreach($closed as $idx2 => $tag2) {
        if($tag2 == $tag) {
          unset($opened[$idx]);
          unset($closed[$idx2]);
          break;
        }
      }
    }
  }

  if($opened) {
    $tagstoclose = array_reverse($opened);
    foreach($tagstoclose as $tag) $input .= "</$tag>";
  }

  return $input;
}

?>
<div class="muse_content">
<div class="page">

<h1>Search Muse's Success</h1>

<p>Type your search term here:</p>

<form action="<?php echo site_url('search/results'); ?>" method="post" id="cse-search-box">
  <div>
    <input type="text" name="q" size="31" value="<?php echo $q; ?>" x-webkit-speech="x-webkit-speech" speech="speech" onwebkitspeechchange="this.form.submit();" />
    <input type="submit" name="sa" value="Search" />
  </div>
</form>


<?php if (count($results) >= 1) { ?>

<p><?php echo $num_qued; ?> (<?php echo $time_taken; ?> seconds) </p>

<hr />

<p><?php echo $page_links; ?></p>

<?php foreach ($results as $item) { ?>

<p class="mini_listing">
<a href="<?php echo $item['url']; ?>"><strong><?php echo $item['title']; ?></strong></a> by <?php echo $item['author']; ?>
<?php echo $item['summary']; ?>
</p>

<?php } ?>
<p><?php echo $page_links; ?></p>
<?php } else { echo '<p>Your search - <strong>'.$q.'</strong> - did not match any documents.</p>

<p>Suggestions:</p>
<ul>
<li>Make sure all words are spelled correctly.</li>
<li>Try different keywords.</li>
<li>Try more general keywords.</li></ul>'; } ?>

</div>

</div>

<div class="ad">
<?php $this->load->view("ads/sidebar"); ?>
</div>

<div class="clear"></div>