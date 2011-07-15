<html>
<head>
<title>Error</title>
</head>
<body>
<table border="1" cellspacing="0" cellpadding="0" style="margin: auto;">
	<tr>
		<th colspan="2" style="padding: 5px; background-color:#5F9EF8;">Exception Information</th>
	</tr>
	<tr>
		<th width="60px" style="padding: 5px; background-color:#5F9EF8;">Type</th>
		<td style="padding: 5px; background-color:#FFFFFF;"><?php echo $type; ?></td>
	</tr>
	<tr>
		<th width="60px" style="padding: 5px; background-color:#5F9EF8;">Code</th>
		<td style="padding: 5px; background-color:#FFFFFF;"><?php echo $code; ?></td>
	</tr>
	<tr>
		<th style="padding: 5px; background-color:#5F9EF8;">Desc</th>
		<td style="padding: 5px; background-color:#FFFFFF;"><?php echo $message; ?></td>
	</tr>
	<tr>
		<th style="padding: 5px; background-color:#5F9EF8;">File</th>
		<td style="padding: 5px; background-color:#FFFFFF;"><?php echo $file; ?></td>
	</tr>
	<tr>
		<th style="padding: 5px; background-color:#5F9EF8;">Line</th>
		<td style="padding: 5px; background-color:#FFFFFF;"><?php echo $line; ?></td>
	</tr>
	<tr>
		<th style="padding: 5px; background-color:#5F9EF8;">Trace</th>
		<td style="padding: 5px; background-color:#FFFFFF;"><pre style="width: 400px; overflow-x: auto;"><?php echo $trace; ?></pre></td>
	</tr>
</table>
</body>
</html>
