<?php include('access.php'); ?>
<title>information</title>
<style>
.table-yes { background: #9F9; vertical-align: middle; text-align: center; }
.table-no { background: #F99; vertical-align: middle; text-align: center; }
.table-unknown { background: #CCC; vertical-align: middle; text-align: center; }
th[scope="row"] { text-align: left; }
</style>
</head>
<body>
<h1>information</h1>
<details>
<summary><h2>how to make an eBook</h2></summary>
<p>do this myself to verify that it works</p>
<ol>
<li>scan the pages of a physical book into digital images</li>
<li>add the images to a .pdf using adobe acrobat (where? tools add pages?)</li>
<li>edit metadata (Ctrl+D)</li>
</ol>
<form action="add_ebook_form.php" method="post">
<p>then you may
<?php print(hidden_form_inputs($access_credentials, $tab_settings, $_REQUEST)); ?>
<input type="submit" value="add an eBook" class="button_as_link" />
 so that others may access it and you may earn <img src="images/EX.png" alt="EX" /> currency</p>
</form>
</details>
<details>
<summary><h2>the currency</h2></summary>
<p>The name of the currency used on the Open Exploration Society website is EX. This may invoke the ideas of exploration or experience. It is designed to avoid problems that other currencies have. Namely:</p>
<ul>
<li>hoarding currency does not grant power</li>
<li>currency creation is not centralized or done by a corrupt group</li>
</ul>
<!--p>Specifically: an account generates 0.001 EX every second.</p-->
<p>Specifically: an account generates 1 <img src="images/EX.png" alt="EX" /> every second.</p>
</details>
<details>
<summary><h2>how to download files from a torrent</h2></summary>
<p>downloading by torrent rather than directly is more decentralized and thus more adaptive. An easy way to download using torrents is to download the good and zero-charge torrent client <a href="https://www.tixati.com/">tixati</a>.</p>
<ul>
<li><a href="https://www.tixati.com/findcontent/">Guide on how to use tixati</a></li>
</ul>
</details>
<details>
<summary><h2>opening eBook files</h2></summary>
<!--https://en.wikipedia.org/wiki/Comparison_of_e-book_formats-->
<!--https://www.the-ebook-reader.com/ebook-formats.html-->
<!--https://fileinfo.com/filetypes/ebook-->
<!-- may be better to store this as a multidimensional array and print the table as needed; shrug -->
<!-- would be good to link these to their website -->
<table border="1" cellspacing="0" cellpadding="4">
<caption><b>eBook compatibility</b></caption>
<thead>
<tr>
<td></td>
<td></td>
<th scope="col">.azw</th>
<th scope="col">.cbr</th>
<th scope="col">.djvu</th>
<th scope="col">.doc</th>
<th scope="col">.docx</th>
<th scope="col">.epub</th>
<th scope="col">.fb2</th>
<th scope="col">.html</th>
<th scope="col">.ibook</th>
<th scope="col">.inf</th>
<th scope="col">.lit</th>
<th scope="col">.mobi</th>
<th scope="col">.pdb</th>
<th scope="col">.pdf</th>
<th scope="col">.ps</th>
<th scope="col">.oxps</th>
<th scope="col">.txt</th>
<th scope="col">.xps</th>
</tr>
</thead>
<tbody>
<tr>
<th scope="rowgroup" rowspan="28">software</th>
<th scope="row">Atavist</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Bibliovore</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Bookviser Reader</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Calibre</th>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">CBR Reader</th>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Creatavist</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">CreateSpace</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">eBook Maestro PRO</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">EPUB File Reader</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">FBReader</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Freda</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">FSS ePUB Reader</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Google Books Downloader</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">iBooks Author</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Icecream eBook Reader</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Jutoh</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Kindle for PC</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Mobi File Reader</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Pages</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Pressbooks</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Reedsy Book Editor</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Scrivener</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Sigil</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">SumatraPDF</th>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
</tr>
<tr>
<th scope="row">Ultimate eBook Creator</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Vellum</th>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
</tr>
</tbody>
<tbody>
<tr>
<th scope="rowgroup" rowspan="27">hardware</th>
<th scope="row">Amazon Kindle 1</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Amazon Kindle 2, DX</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Amazon Kindle 3</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Amazon Kindle Fire</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Android Devices</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Apple iOS Devices</th>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Azbooka WISEreader</th>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Barnes &amp; Noble Nook</th>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Barnes &amp; Noble Nook Color</th>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Bookeen Cybook Gen3, Opus</th>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">COOL-ER Classic</th>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Foxit eSlick</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Hanlin e-Reader V3</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Hanvon WISEreader</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">iRex iLiad</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Iriver Story</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Kobo eReader</th>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Nokia N900</th>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">NUUTbook 2</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">OLPC XO, Sugar</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Onyx Boox 60</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">TrekStor eBook Reader Pyrus</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Pocketbook 301 Plus, 302, 360&deg;</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Pocketbook Aqua</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Sony Reader</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Viewsonic VEB612</th>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
<tr>
<th scope="row">Windows Phone 7</th>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-no">no</td>
<td class="table-no">no</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
<td class="table-unknown">?</td>
<td class="table-yes">yes</td>
<td class="table-unknown">?</td>
</tr>
</tbody>
</table>
</details>
<?php include('includes' . DS . 'html_end.incl'); ?>