<?php
session_start();

/*
	// For testing, automatically empty the chunks folder on load
	$files = glob('files/chunks/*'); // get all file names
	foreach($files as $file) {
		if(is_file($file))
		unlink($file); // delete file
	}
	$files = glob('files/r/*'); // get all file names
	foreach($files as $file) {
		if(is_file($file))
		unlink($file); // delete file
	}
	$files = glob('tsvs/r/*'); // get all file names
	foreach($files as $file) {
		if(is_file($file))
		unlink($file); // delete file
	}
	if(is_file('files/merge.tsv'))
	unlink('files/merge.tsv');

	if(is_file('chunks.zip'))
	unlink('chunks.zip');
*/
// If user clicked start over destroy the session and delete uploads
if (isset($_GET['action']) && $_GET['action'] == "clear") {
	unset($_SESSION['uploaded_files']);

	$folders = glob('sessions/' . session_id() . '/*');

	foreach ($folders as $folder) {
		if(is_dir($folder)) {
			$files = glob($folder . '/*');
			foreach($files as $file) {
				if(is_file($file))
				unlink($file); // delete file
			}
			rmdir($folder);
		}
		else {
			unlink($folder);
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Hypercutter</title>
<link rel="stylesheet" type="text/css" media="all" href="styles.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js'></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" type="text/css" media="all" />
<script src="tooltips.js"></script>
<script>
 $(function() {
        $( "#dialog-modal" ).dialog({
			autoOpen: false,
            height: 500,
			width: 700,
            modal: true,
            resizable: false,
			show: 'slide',
			hide: 'drop'
        });
		$('#about').click(function(){ $('#dialog-modal').dialog('open'); });
    });

<!-- Scrubbing Option Dialogs -->
$(function() {
        var file = $( "#swfileselect" );
 
        $( "#dialog-stopwords" ).dialog({
            autoOpen: false,
            height: 300,
            width: 400,
            modal: true,
			show: 'bounce',
			hide: 'puff',
            buttons: [{
				//id: 'swupload',
				//text: 'Upload'
				//,
				//click:
				//	function(){
						//alert("Stopword list: " + //document.getElementById('stopwordlist').value);
						//$( this ).dialog( "close" );
				//	}
				//},
				//{
				text: 'Delete List',
				click:
					function() {
						$("#swfileselect").val(""); // May not work in all browsers to delete the filename
						$("#swfiledrag").empty();
						$("#swprogress").empty();
						$("#stopwordlist").val("");
						$(document).data("swlist", "");
						$("#swmessages").empty();
						//$( this ).dialog( "close" );
					}
				}
			]
        });
 
        $('#stopwords').click(function(){ $('#dialog-stopwords').dialog('open'); });
    });

$(function() {
        var file = $( "#lemmafileselect" );
 
        $( "#dialog-lemmas" ).dialog({
            autoOpen: false,
            height: 300,
            width: 400,
            modal: true,
			show: 'bounce',
			hide: 'puff',
            buttons: [{
				//id: 'lemmaupload',
				//text: 'Upload'
				//,
				//click:
				//	function(){
						//alert("Lemma list: " + //document.getElementById('lemmalist').value);
						//$( this ).dialog( "close" );
				//	}
				//},
				//{
				text: 'Delete List',
				click:
					function() {
						$("#lemmafileselect").val(""); // May not work in all browsers to delete the filename
						$("#lemmafiledrag").empty();
						$("#lemmaprogress").empty();
						$("#lemmalist").val("");
						$(document).data("lemmalist", "");
						$("#lemmamessages").empty();
						//$( this ).dialog( "close" );
					}
				}
			]
        });

        $('#lemmas').click(function(){ $('#dialog-lemmas').dialog('open'); });
    });
	
$(function() {
        var file = $( "#consolidationsfileselect" );
 
        $( "#dialog-consolidations" ).dialog({
            autoOpen: false,
            height: 300,
            width: 400,
            modal: true,
			show: 'bounce',
			hide: 'puff',
            buttons: [{
				//id: 'consolidationsload',
				//text: 'Upload'
				//,
				//click:
				//	function(){
						//alert("Consolidations List: " + //document.getElementById('consolidationslist').value);
						//$( this ).dialog( "close" );
				//	}
				//},
				//{
				text: 'Delete List',
				click:
					function() {
						$("#consolidationslist").val("");
						$("#consolidationsfileselect").val(""); // May not work in all browsers to delete the filename
						$("#consolidationsfiledrag").empty();
						$("#consolidationsprogress").empty();
						$("#consolidationslist").val("");
						$(document).data("consolidationslist", "");
						$("#consolidationsmessages").empty();
						//$( this ).dialog( "close" );
					}
				}
			]
        });
 
        $('#consolidations').click(function(){ $('#dialog-consolidations').dialog('open'); });
    });
	
$(function() {
        var file = $( "#specialcharsfileselect" );
 
        $( "#dialog-specialchars" ).dialog({
            autoOpen: false,
            height: 400,
			width: 400,
            modal: true,
			show: 'bounce',
			hide: 'puff',
            buttons: [{
				//id: 'specialcharsload',
				//text: 'Upload'
				//,
				//click:
				//	function(){
						//alert("Specialchars List: " + //document.getElementById('specialcharslist').value);
						//$( this ).dialog( "close" );
				//	}
				//},
				//{
				text: 'Delete List',
				click:
					function() {
						$("#specialcharslist").val("");
						$("#specialcharsfileselect").val(""); // May not work in all browsers to delete the filename
						$("#specialcharsfiledrag").empty();
						$("#specialcharsprogress").empty();
						$("#specialcharslist").val("");
						$(document).data("specialcharslist", "");
						$("#specialcharsmessages").empty();
						//$( this ).dialog( "close" );
					}
				}
			]
        });
 
        $('#specialchars').click(function(){ $('#dialog-specialchars').dialog('open'); });
    });
<!-- End of Scrubbing Option Dialogs -->
	
 $(function() {
        $( "#cluster-modal" ).dialog({
			autoOpen: false,
            height: 400,
			width: 700,
            modal: true,
            resizable: false,
			show: 'scale',
			hide: 'scale'
        });
		$('#cluster').click(function(){ $('#cluster-modal').dialog('open'); });
    });
</script>
</head>
<body>
<div id="wrap">
<h1>Hypercutter</h1>
<table>
<tr>
<td><button id="about">About This Tool</button></td>
<?php
if(isset($_SESSION['uploaded_files'])) {
// Script generates Strict Standards: Only variables should be passed by reference unless error reporting is changed.
error_reporting(4);

// Replace this with file upload script
$textarray = array("Was","this","the","face","that","launched","a","thousand","ships");
$directory = "hypercutter/uploads/" . session_id();
$chunksize = $_SESSION['chunksize'];
$chunknumber = $_SESSION['chunknumber'];
$shiftsize = $_SESSION['shiftsize'];
$lastprop = $_SESSION['lastprop'];
/*
// Grab all the filenames ending in .txt
foreach (glob("$directory/*.txt") as $filename) {
	// For each file, get the contents as $data
	$data = file_get_contents($filename);
	$textarray = explode(" ", $data);
	// call the cutter function on the text array
	$chunkarray = cutter($textarray, $chunksize, $shiftsize, $lastprop);
	// write a new file for each chunk
	// separate function needed to calculate data
	// write a csv with the format
// AncreneWisse_2000_words_05_02000.txt	2000	608	357
// RANK	WORD	COUNT	"RELATIVE FREQUENCY"
// 1	þe	145	0.0725
// cf. chunkset.php

}
*/
	// cutter()
	// cuts the input array into specified chunks
	// ARGS:
	//	$textarray: array containing the words of the text in
	//		individual slots
	// 	$chunksize: the size in words of a chunk
	//	$shiftsize: the number of words to shift
	// 	$lastprop: the proportion of a chunk the last chunk can be
	// RETURN: an array of chunks, where a chunk is a subset of 
	//		the input array indexed by the first and last word number
	//		in the chunk, each chunk will not necessarily be indexed
	//		by word number, but will be textual order
	function cutter( $textarray, $chunksize, $shiftsize, $lastprop ) {

		// set initial chunk
		$start = 0;
		$end = $chunksize;
		// grab the next chunk and add it in if the bounds were not exceeded
		while ( $chunk = array_subset( $textarray, $start, $end ) ) {

			// create the index of the $start..$end, most of the time,
			// if the subset came back having stopped at MAX, we'll
			// need the last key in the $chunk array
			$index = "$start.." . array_pop( array_keys( $chunk ) );
			$chunkarray[$index] = $chunk;

			// get new bounds
			$start += $shiftsize;
			$end += $shiftsize;

		}

		// determine the min size of the last chunk
		// err on the side of too much; better to have a chunk of
		// 4 in chunksize 3 than a chunk of 1
		$lastsize = ceil( $chunksize * $lastprop );

		// find the last chunk
		$lastchunk = end( $chunkarray );

		// the the size of the last chunk is smaller than allowed, 
		//  merge and reindex
		if ( count( $lastchunk ) < $lastsize ) {
			
			// discard the offending chunk
			array_pop( $chunkarray );

			// get the very final index of the last chunk, last word
			$indexend = array_pop( array_keys( $lastchunk ) );

			// remove and capture the chunk to append to
			$secondlast = array_pop( $chunkarray );

			// get the first index of that array and prepend 
			//  that to create the new index to $chunkarray
			$index = array_shift( array_keys( $secondlast ) ) . "..$indexend";
		
			// merge the two chunks in order, and stick it on
			$newchunk = array_merge( $secondlast, $lastchunk );
			$chunkarray[$index] = $newchunk;

		}

		return $chunkarray;

	}

	// array_subset()
	// dumb PHP doesn't have this function, so this is a hacky
	// version that doesn't think about non-numeric keys
	// ARGS:
	//	$array: array indexed by numbers
	//	$start: index of first element in subset
	//	$end: index of first element not in array
	// RETURN: an array [$start,$end) from $array with
	//		the same indicies 
	function array_subset( $array, $start, $end ) {
	
		$MAX = count( $array );
		//$subset = array();
		for ( $i = $start; $i < $end; $i++ ) {

			if ( $i >= $MAX )
				break;
			$subset[$i] = $array[$i];

		}

		if ( count( $subset ) == 0 )
			return null;
		else
			return $subset;

	}


	function count_words( &$textarray ) 
	{
	    $wordcount = array();
	    // iterate through the array of words and up the count
	    foreach ( $textarray as $word ) 
	    {
	        if ( $word == "" )
	            continue;
	        $wordcount[ "$word" ] = isset( $wordcount[ "$word" ] ) ? 
	                $wordcount[ "$word" ] + 1 : 1;
	    }

	    return $wordcount;

	}

	function hash_sort( &$hash, $sort ) {

    if ( $sort == 'c' ) {

        // grab array for word and counts
        $word = array_keys( $hash );
        $count = array_values( $hash );
        
        // sort the counts, then words in $hash
        array_multisort( $count, SORT_DESC, $word, SORT_ASC, $hash );

    }
    else {

        // sort by key, ie. the word name
        ksort( $hash );

    }

}

echo '<td><form action="index.php?action=clear" method="POST">
<input type="submit" value="Start Over"/>
</form></td></tr></table><p>
<fieldset><legend>Download</legend>
<table><tr>
<td><button id="cluster">Dendrogram</button></td>
<td><form action="chunks.php" method="POST">
<input type="submit" value="Chunks"/>
</form></td>
<td><form id="download_tsv" action="download_tsv.php" method="POST">
<input type="submit" value="Merged TSV"/>
<input name="transpose" type="checkbox" checked/> <label>Transpose</label>
</form></td>
</tr>
</table>
</fieldset></p>';

$punctuation = ($_SESSION["punctuationbox"] == "on") ? "yes" : "no";
$apos = ($_SESSION["aposbox"] == "on") ? "yes" : "no"; 
$hyphens = ($_SESSION["hyphensbox"] == "on") ? "yes" : "no";
$digits = ($_SESSION["digitsbox"] == "on") ? "yes" : "no"; 
$lc = ($_SESSION["lowercasebox"] == "on") ? "yes" : "no";
if (isset($_SESSION["tags"])) {
	$tags = $_SESSION["tags"];
}
if (isset($_SESSION["commonbox"])) {
	$tags = $_SESSION["commonbox"];
}

echo "<hr>";
?>
<script>
  function openDialog(title, content) {
    switch (content) {
		case "stopwords":
		content = "<?php echo preg_replace('/\s+/', '', nl2br($_SESSION["stopwordlist"])) ?>";
		break;
		case "lemmas":
		content = "<?php echo preg_replace('/\s+/', '', nl2br($_SESSION["lemmalist"])) ?>";
		break;
		case "consolidations":
		content = "<?php echo preg_replace('/\s+/', '', nl2br($_SESSION["consolidationslist"])) ?>";
		break;
		case "specialchars":
		content = "<?php echo preg_replace('/\s+/', '', nl2br($_SESSION["specialcharslist"])) ?>";
	
	}
    $( '<div title="' + title + '">' + content + '</div>' ).dialog({
			width: 250,
			height: 350,
			modal: true
		});
  }
</script>
<?php
echo "<table width=\"650\">";
echo "<tr><td colspan=\"3\"><b>Options:</b></td></tr>";
echo "<tr><td width=\"200\">", ($_SESSION['chunksize'] ? "Chunk Size: " . $_SESSION['chunksize'] : "Number of Chunks: " . $_SESSION['chunknumber']) . "</td>";
echo "<td width=\"200\">Overlap: " . $_SESSION['overlap'] . "</td>";
echo ($_SESSION['chunksize'] ? "<td width=\"200\">Last Proportion: " . $_SESSION['lastprop'] * 100 . "%</td></tr>" : "</tr>");
echo "<tr><td width=\"250\">Remove Punctuation: " . $punctuation . "</td>";
echo "<td width=\"200\">Keep Apostrophes: " . $apos . "</td>";
echo "<td width=\"200\">Keep Hyphens: " . $hyphens . "</td></tr>";
echo "<tr><td width=\"250\">Remove Digits: " . $digits . "</td>";
echo "<td width=\"200\">Make Lowercase: " . $lc . "</td>";
echo ($_SESSION['stopwordorderbox'] == "on") ? "<td width=\"250\">Stopwords Filtered before Chunking</td>" : "<td width=\"250\">&nbsp;</td>";
echo "</tr>";
if ($_SESSION['stopwordlist'] != "") {
	$stopwords = $_SESSION['stopwordlist'];
	echo "<tr><td colspan=\"3\">Stopwords removed (<a href=\"#\" onclick=\"openDialog('Stopword List', 'stopwords');\">View List</a>)</td></tr>";
}
if ($_SESSION['lemmalist'] != "") {
	$lemmas = $_SESSION['lemmalist'];
	echo "<tr><td colspan=\"3\">Lemma List Applied (<a href=\"#\" onclick=\"openDialog('Lemma List', 'lemmas');\">View List</a>)</td></tr>";
}
if ($_SESSION['consolidationslist'] != "") {
	$consolidations = $_SESSION['consolidationslist'];
	echo "<tr><td colspan=\"3\">Consolidation List Applied (<a href=\"#\" onclick=\"openDialog('Consolidations', 'consolidations');\">View List</a>)</td></tr>";
}
if ($_SESSION['specialcharslist'] != "") {
	$specialchars = $_SESSION['specialcharslist'];
	echo "<tr><td colspan=\"3\">Special Character Rules Applied (<a href=\"#\" onclick=\"openDialog('Special Character Rules', 'specialchars');\">View List</a>)</td></tr>";
}
echo "</table>";
echo "<hr>";

// Duplicate file names cause the loop to break, incomplete page load, and thus disabling of JQuery
$_SESSION['uploaded_files'] = array_unique($_SESSION['uploaded_files']);

// Loop through the source files and chunk each one.
foreach ($_SESSION['uploaded_files'] as $sourcefile) {
	echo "<h3>".$sourcefile."</h3>";
	$text = file_get_contents('sessions/'.session_id().'/uploads/'.$sourcefile);
	ob_start();
	// Scrub the text
	include("scrubbing_functions.php");
	$myContent = ob_get_clean();
	// Make the text an array and chunk it
	$textarray = explode(" ", $text);

	if ($_SESSION['chunkoption'] == "size") {
		$chunkarray = cutter($textarray, $chunksize, $shiftsize, $lastprop);
		$chunknumber = count($chunkarray);
	}
	else {
		$chunksize = ceil(count($textarray)/$chunknumber);
		$chunkarray = cutter($textarray, $chunksize, $chunksize, 0);
	}


// Build the output
$i = 1;
$padlength = intval(log10(count($chunkarray))) + 1;
foreach ($chunkarray as $range=>$tokens) {
 	$outrange = str_replace("..", "-", $range);
	$printrange = str_replace("..", " to ", $range);
	$outfile = rtrim($sourcefile, ".txt") . str_pad($i, $padlength, "0", STR_PAD_LEFT) . "_" . $outrange;
	$header = "Chunk " . str_pad($i, $padlength, "0", STR_PAD_LEFT) . " - Tokens " . $printrange . " (" . $outfile . ")";
	echo "<b>" . $header . "</b><br>";
	$str = implode(" ", $tokens);
	## Filter stopwords if there is a stopword list *and* the filter stopwords before chunking checkbox is off ##
	if (isset($_SESSION['stopwordlist']) && $_SESSION['stopwordorderbox'] == "off") {
		$str = trim(remove_stopWords($str, $stopwords));
	}	
	echo $str;
	echo "<hr>";
	$i++;
	// Write the header and string to a file here.
	$out = $header . "\n" . $str;
	if (!is_dir('sessions/' . session_id() . '/chunks')) {
		mkdir('sessions/' . session_id() . '/chunks');
	}
	$outdirectory = 'sessions/' . session_id() . '/chunks/'; // Needs a directory path
	$chunkfile = $outdirectory . $outfile . ".txt";
	file_put_contents($chunkfile, $str);



	$wordcount = count_words($tokens);
	if (!is_dir('sessions/' . session_id() . '/tsvs')) {
		mkdir('sessions/' . session_id() . '/tsvs');
	}
	$tsvdirectory = ('sessions/' . session_id() . '/tsvs/');
	hash_sort($wordcount, 'c');
	$outtsv = urldecode(http_build_query($wordcount, '', "\t"));
	$tsvfile = $tsvdirectory . $outfile . ".tsv";
	file_put_contents($tsvfile, $outtsv);

}

}
echo "<hr><hr>";

// Output generated, so delete the file uploads
$files = glob('hypercutter/sessions/' . session_id() . '/uploads/*'); // get all file names
foreach($files as $file) {
	if(is_file($file))
	unlink($file); // delete file
}
// No session variable
} else {
?>
</tr>
</table>
<p></p>
<form id="upload" action="upload.php" method="POST" enctype="multipart/form-data" onSubmit="return nochunksize();">

<fieldset>
<legend>Bulk File Upload</legend>

<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="1000000" />
<div>
	<label for="fileselect">Files to upload:</label>
	<input type="file" id="fileselect" name="fileselect[]" multiple="multiple" />
	<div id="filedrag">Or drop files here</div>
</div>

</fieldset>

<?php
$punctuationbox = (isset($_SESSION["punctuationbox"])) ? $_SESSION["punctuationbox"] : "on"; 
$aposbox = (isset($_SESSION["aposbox"])) ? $_SESSION["aposbox"] : "off";
$hyphensbox = (isset($_SESSION["hyphensbox"])) ? $_SESSION["hyphensbox"] : "off";
$digitsbox = (isset($_SESSION["digitsbox"])) ? $_SESSION["digitsbox"] : "on";
$lowercasebox = (isset($_SESSION["lowercasebox"])) ? $_SESSION["lowercasebox"] : "on";
$aposbox = (isset($_SESSION["tags"])) ? $_SESSION["tags"] : "keep";
$commonbox = (isset($_SESSION["commonbox"])) ? $_SESSION["commonbox"] : "on";
$stopwordorderbox = (isset($_SESSION["stopwordorderbox"])) ? $_SESSION["stopwordorderbox"] : "off";

// Conditional disabled because of multiple file upload -- handled by hidden input updated by filedrag.js.
//if(preg_match("'<[^>]+>'U", $file) > 0) {
//    $_SESSION["POST"]["formattingbox"] = "on";
//}
echo '<input type="hidden" name="formattingbox" id="formattingbox" value="';
echo ($_SESSION["formattingbox"] == "on") ? "on" : "off";
echo '"/>';
?>

<fieldset>
<legend>Scrubbing Options</legend>
<table width="450">
<tr>
	<td colspan="2">
	<input type="checkbox" name="punctuationbox" <?php echo ($punctuationbox == "on") ? "checked" : "" ?>/> <label for="punctuation" value="yes">Remove Punctuation</label>
	</td>
</tr>
<tr>
	<td width="50%">
		<input type="checkbox" name="aposbox"  <?php echo ($aposbox == "on") ? "checked" : "" ?>/> <label>Keep Apostrophes</label>
	</td>
	<td width="50%">
		<input type="checkbox" name="hyphensbox"  <?php echo ($hyphensbox == "on") ? "checked" : "" ?>/> <label>Keep Hyphens</label>
	</td>
</tr>
<tr>
	<td width="50%">
	<input type="checkbox" name="digitsbox" <?php echo ($digitsbox == "on") ? "checked" : "" ?>/> <label for="numbers">Remove Digits</label>
	</td>
	<td width="50%">
	<input type="checkbox" name="lowercasebox" <?php echo ($lowercasebox == "on") ? "checked" : "" ?>/>Make Lowercase</label></td></tr>
<tr class="taghandler" style="display:none;">
	<td colspan="2">Your text(s) contain tags. Would you like to<br />
	&nbsp;&nbsp;&nbsp;<input type="radio" name="tags" value="keep" checked /> Keep Words Inside Tags<br />
	&nbsp;&nbsp;&nbsp;<input type="radio" name="tags" value="discard"  /> Discard Words Inside Tags
	</td>
</tr>	
<tr>
	<td width="50%">
		<a id="stopwords" href="#">Load Stopword List</a> <img valign="bottom" src="question_mark.png" alt="Question Mark" title="Click the link to upload a stopword list (a text file with each stopword separated by a space or a comma)." />
	</td>
	<td width="50%">
		<a id="lemmas" href="#">Load Lemma List</a> <img valign="bottom" src="question_mark.png" alt="Question Mark" title="Click the link to upload a lemma list. Each lemma should be on a separate line. Word types associated with each lemma should take the form of a comma-separated list following the lemma (e.g. 'sing, sang, sung' for the lemma 'sing')." />
	</td>
</tr>
<tr>
	<td width="50%">
		<a id="consolidations" href="#">Load Consolidations List</a> <img valign="bottom" src="question_mark.png" alt="Question Mark" title="Click the link to upload a consolidations list. Each line should contain a comma-separated list containing character pattern followed by a the values to replace with the pattern (e.g. 
		'a, aa, bb' will replace the second two forms with the first)." />
	</td>
	<td width="50%">
		<a id="specialchars" href="#">Special Characters Handling</a> <img valign="bottom" src="question_mark.png" alt="Question Mark" title="Click the link to upload a list is of rules for handling special character entities." />
	</td>
</tr>
</table>
<input type="hidden" id="stopwordlist" name="stopwordlist" value="" />
<input type="hidden" id="stopwordorderbox" name="stopwordorderbox" value="off" />
<input type="hidden" id="lemmalist" name="lemmalist" value="" />
<input type="hidden" id="consolidationslist" name="consolidationslist" value="" />
<input type="hidden" id="specialcharslist" name="specialcharslist" value="" />
<input type="hidden" id="commonlist" name="commonlist" value="" />
<input type="hidden" id="entityrulesopts" name="entityrulesopts" value="default" />
</fieldset>
<!-- All values are defined in the form. Modal dialog uploads will call javascript functions to update the values prior to submission of the form. These functions are in individualised versions of filedrag.js. I think the consolidations function should be replaced with a custom regex pattern function. It's almost the same thing. -->


<fieldset>
<legend>Chunk Settings</legend>
<label>Split by:</label>
<input type="radio" name="chunkoption" value="size" checked onClick="hidechunkoption(1);">Chunk Size
<input type="radio" name="chunkoption" value="number" onClick="hidechunkoption(2);">Number of Chunks
<p><label>Chunk Size:</label> <input name="chunksize" id="chunksize" type="text" size="12"/> (No. words per chunk)</p>
<p><label>Number of Chunks:</label> <input name="chunknumber" id="chunknumber" type="text" size="12" disabled/></p>
<p><label>Overlap:</label> <input name="shiftsize" type="text" size="12" value="0"/> (No. words overlapping at chunk boundaries)</p>
<p><label>Last Proportion:</label> <input name="lastprop" id="lastprop" type="text" size="3" value="50"/>% (The proportion of chunksize the last chunk can be)</p>
</fieldset>

<p><input type="submit" value="Scrub &amp; Chunk Files"/></p>
<input type="hidden" id="checkTags" name="checkTags" value="false"/>
</form>

<div id="progress"></div>

<div id="messages">
<p>Status Messages</p>
</div>

<script src="filedrag.js"></script>

<?php
}
?>
<div id="dialog-modal" title="About Hypercutter">
    <p>Hypercutter streamlines some of the functions of Scrubber and Divitext. It allows you to set some basic scrubbing and chunking options, then upload multiple files to be processed. The file upload function is wrapped in an <a href="http://blogs.sitepointstatic.com/examples/tech/filedrag/3/index.html" target="_blank">HTML5 file drag &amp; drop API</a> that uses asynchronous Ajax file uploads, graphical progress bars, and progressive enhancement. Note that it does not work in Internet Explorer. After selecting files, wait until the green progress bar shows that they have been uploaded.  It will flick to bright, lime green when ready. Then click the "Scrub &amp; Chunk Files" button. The files will be scrubbed and chunked according to your criteria. You may then download the chunked files by clicking the "Download Chunks" link. Click the "Start Over" to upload a new set of texts. <b>When you are done, please click "Start Over" to clear the "chunks" folder.</b></p>
	<p>Notes:</p>
	<ol>
        <li>The zip archive download script seems to be very unpredictable, often generating empty archives. I got it working right before bed, but no guarantees...</li>
        <li>For some reason, the web output is showing the chunks twice, although it's not too annoying, as it goes through all texts before starting over. The downloaded texts are correct.</li>
	<li>The scrubbing functions remove all non-alphabetic characters, change accented characters to non-accented ones (e.g. <i>&eacute;</i> becomes <i>e</i>), convert to lowercase, and create an array of tokens. The text is assumed to be in UTF-8, and the script handles <i>&eth;</i>, <i>&thorn;</i>, <i>&aelig;</i>, and <i>&#540;</i> quite well. There is currently no provision for texts containing entities like "&amp;eth;" The ampersands and semicolons will be stripped.</li>
	<li>Most of the code for chunking is copied from Divitext. Divitext's $shiftsize variable is marshalled to create overlaps. The user-supplied chunk size minus overlap = $shiftsize. So 100-word chunks with an overlap of 90 will cause chunks to overlap by 10 words.</li>
	<li>Maximum file size is 1,000,000 bytes, but it can be changed.</li>
	<li>The percentage entered for the last proportion is divided by 100 (e.g. 50% becomes .5). I hope that's right.</li>
	<li>Files are uploaded into an "uploads" folder, which is emptied once the output is generated. Chunks are saved in a "chunks" folder, which is also emptied when the chunks are downloaded, when you click "Start Over", or when the tool is reloaded with a new session. This obviously has to be re-written with user-specific sessions and folders to prevent conflicts.</li>
        <li>A better progress bar is needed because it is hard to see when the fade-in one is finished.</li>
	<li>The downloadable zip archive only contains files of chunked texts. The next step will be to generate the stats &agrave; la Divitext.</li>
	</ol>
</div>

		<div id="dialog-stopwords" title="Upload Stopword List">
			<script>
			function setStopWordOrder() {
				if(stopwordorderbox2.checked == true) {
					document.getElementById('stopwordorderbox').value = "on";
				}
				else {
					document.getElementById('stopwordorderbox').value = "off";
				}
				//alert(document.getElementById('stopwordorderbox').value);
			}
			</script>
		
				<p><input type="checkbox" id="stopwordorderbox2" name="stopwordorderbox2" <?php echo ($stopwordorderbox == "on") ? "checked" : "" ?> onClick="setStopWordOrder();"/> Apply stopwords 
				before chunking <img valign="bottom" src="question_mark.png" alt="Question Mark" title="**This function is not yet active** Stopwords 
				are normally deleted after chunking so that the chunk boundaries will match those of an unfiltered text. If you wish to filter 
				stopwords <em>before</em> chunking has taken place (which will cause inconsistent decreases in the size of your chunks), check this box." /></p>
				<label for="swfileselect">File to upload:</label>
				<input type="file" id="swfileselect" name="swfileselect[]" />
				<div id="swfiledrag"></div>
		
			<div id="swprogress"></div>

			<div id="swmessages" style="font-size:10px;">
				<!--<p>Status Messages</p>-->
			</div>
		</div>
			<!-- Filedrag Script -->
			<script src="filedrag/swfiledrag.js"></script>

		<div id="dialog-lemmas" title="Upload Lemma List">
		
				<label for="lemmafileselect">File to upload:</label>
				<input type="file" id="lemmafileselect" name="lemmafileselect[]" />
				<div id="lemmafiledrag"></div>
		
			<div id="lemmaprogress"></div>

			<div id="lemmamessages" style="font-size:10px;">
				<!--<p>Status Messages</p>-->
			</div>
		</div>
			<!-- Filedrag Script -->
			<script src="filedrag/lemmafiledrag.js"></script>

			<div id="dialog-consolidations" title="Upload Consolidation List">
		
				<label for="consolidationsfileselect">File to upload:</label>
				<input type="file" id="consolidationsfileselect" name="consolidationsfileselect[]" />
				<div id="consolidationsfiledrag"></div>
		
			<div id="consolidationsprogress"></div>

			<div id="consolidationsmessages" style="font-size:10px;">
				<!--<p>Status Messages</p>-->
			</div>
		</div>
			<!-- Filedrag Script -->
			<script src="filedrag/consolidationsfiledrag.js"></script>

			<div id="dialog-specialchars" title="Upload Rules for Handling Special Characters">
			
				<p><b>Use Pre-Defined Rule Set <img src="question_mark.png" alt="Question Mark" title="Currently, the DOE entities <i>&amp;ae;</i>, <i>&amp;d;</i>, <i>&amp;t;</i>, and <i>&amp;#0541;</i> are converted to <i>&aelig;</i>, <i>&eth;</i>, <i>&thorn;</i>, and <i>&#0541;</i> by default (though <i>&#0541;</i> is technically not a DOE entity). The dropdown makes this an option. Early English HTML does the same for HTML entities (e.g. <i>&amp;thorn;</i>), and other entities like <i>&amp;+#383;</i> = <i>ſ</i> could be added to include Early Modern English." /><br />
				(Experimental):</b></p>
				<p>
				<?php
				$entityrules = (isset($_SESSION["entityrules"])) ? $_SESSION["entityrules"] : "default";
				?>
				<script>
				function changeEntityRules() {
					var e = document.getElementById("entityrules");
					var v = e.options[e.selectedIndex].value;
					$("#entityrulesopts").val(v);
					//alert($("#entityrulesopts").val());	
				}
				</script>
				<select id="entityrules" name="entityrules" onchange="changeEntityRules()">
					<option value="default" <?php echo ($entityrules == 'default') ? 'selected="selected"' : '' ?>>Default</option>
					<option value="doe-sgml" <?php echo ($entityrules == 'default') ? '""' : '' ?>>Dictionary of Old English SGML</option>
					<option value="early-english-html"<?php echo ($entityrules == 'default') ? '""' : '' ?>>Early English HTML</option>
				</select>
				</p>
				<p><a href="pre-defined-rules.html" target="_blank">View Pre-Defined Rule Sets</a>
				<p>Or load a custom rule set below.</p>
		
				<label for="specialcharsfileselect">File to upload:</label>
				<input type="file" id="specialcharsfileselect" name="specialcharsfileselect[]" />
				<div id="specialcharsfiledrag"></div>
		
			<div id="consolidationsprogress"></div>

			<div id="specialcharsmessages" style="font-size:10px;">
				<!--<p>Status Messages</p>-->
			</div>
		</div>
			<!-- Filedrag Script -->
			<script src="filedrag/specialcharsfiledrag.js"></script>			
<div id="cluster-modal" title="Generate Dendrogram">
    <form id="cluster" action="cluster.php" method="POST">

	<fieldset>
	<legend>Dendrogram Options</legend>
	
	<p><label>Name:</label> <input name="name" type="text" size="12"/></p>
	<p><label>Linkage Method:</label>
	<select name="method">
  		<option value="average">Average</option>
		<option value="ward">Ward</option>
		<option value="single">Single</option>
		<option value="complete">Complete</option>
		<option value="mcquitty">McQuitty</option>
		<option value="median">Median</option>
		<option value="centroid">Centroid</option>
	</select>
	<p><label>Distance Metric:</label>
	<select name="metric">
  		<option value="euclidean">Euclidean</option>
		<option value="maximum">Maximum</option>
		<option value="manhattan">Manhattan</option>
		<option value="canberra">Canberra</option>
		<option value="binary">Binary</option>
		<option value="minkowski">Minkowski</option>
	</select>
	<p><label>Clustering Output Type:</label>
	<select name="output">
  		<option value="pdf">PDF</option>
		<option value="phyloxml">PhyloXML</option>
	</select>
	</fieldset>
	<p><input type="submit" value="Get Dendrogram"/></p>
	</form>
</div>

</div>

<script type="text/javascript">
function nochunksize()
{
	if (document.getElementById('chunksize').value == '' && document.getElementById('chunknumber').value == '')
	{
		alert('Please enter a chunk size or a number of chunks.')
		return false;
	}
}
function hidechunkoption(num) {

    switch(num)
    {
    	case 1:
    		var show = document.getElementById("chunksize");
    		var hide = document.getElementById("chunknumber");
    		break;
    	case 2:
    		var hide = document.getElementById("chunksize");
    		var show = document.getElementById("chunknumber");
    		break;
    }
    hide.disabled = true;
    hide.value = "";
    show.disabled = false;

    var proportion = document.getElementById("lastprop");
    proportion.disabled ? (proportion.disabled = false, proportion.value = '50') : (proportion.disabled = true, proportion.value = '');
}
</script>

</body>
</html>