<?php
/**
 * Pw_File
 * 
 * @package		Picowa
 * @since		2010-04-09
 */
class Pw_File extends Pico
{
	public function send($filename, $contentType, $path, $type = 'attachment')
	{
		header("Content-type: {$contentType}");
		header("Content-Disposition: {$type}; filename={$filename}");
		header("Content-Length: " . filesize($path));
		readfile($path);
		exit();
	}

	public function download($filename, $path) 
	{
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename={$filename}");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: " . filesize($path));
		readfile($path);
		exit();
	}
}
