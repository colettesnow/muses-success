<h1>Search Muse's Success</h1>

<p>Type your search term here:</p>

<form action="<?php echo site_url('search/results'); ?>" method="post" id="cse-search-box">
  <div>
    <input type="text" name="q" size="31" x-webkit-speech="x-webkit-speech" speech="speech" onwebkitspeechchange="this.form.submit();" />
    <input type="submit" name="sa" value="Search" />
  </div>
</form>