<h1>Submit Updated Information for an Existing Listing - Search Results</h1>

<?php if ($none == true) { ?>
<p class="error">Your search return no results.</p>
<?php } else { ?>
<p>Please select the listing you would like to contribute updated or additional information for from the list:</p>
<?php foreach ($listings as $listing) { ?>
<p><a href="<?php echo $listing['update_url']; ?>"><?php echo $listing['title']; ?></a></p>
<?php } } ?>
<p>If you did not find what you are looking for, you may try your search again:</p>
<form method="post" action="<?php echo site_url('contribute/update_listing'); ?>">

<p>Search for a listing to contribute to:</p>

<p><label>Title:</label> <input type="text" name="title" /></p>
<p><input type="submit" name="search" value="Search Listings" /></p>

</form>

