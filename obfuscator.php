<?php

$excepted_directories = [ '.git', '.idea', 'vendor' ];
$supported_types      = [ 'php' ];
$removed_items        = [ '.git', '.idea', '.gitignore' ];
$plugin_main_file     = [];


echo( '----------- Wp-cloud sso - Plugins Obfuscator ***' . PHP_EOL . PHP_EOL );
$directory           = $argv[1] ?? null;
$directory_base_name = basename( $directory );

check_is_directory_correct( $directory );

echo( 'Loaded directory: ' . $directory . PHP_EOL );

$f_filter = function ( $o_file ) use ( $excepted_directories ) {
	return ! in_array( $o_file->getFilename(), $excepted_directories );
};

$o_dir    = new RecursiveDirectoryIterator( $directory );
$o_filter = new RecursiveCallbackFilterIterator( $o_dir, $f_filter );
$o_iter   = new RecursiveIteratorIterator( $o_filter );


foreach ( $o_iter as $file ) {
	if ( ! is_file_supported( $file, $supported_types ) ) {
		continue;
	}

	$first_comment = get_first_comment_of_file( $file->getPathname() );
	if ( is_plugin_main_file( $first_comment ) ) {
		$plugin_main_file['plugin_info_comment'] = $first_comment;
		$plugin_main_file['filename']            = $file->getFilename();
	}
}

check_is_main_file_found( $plugin_main_file );
echo( 'Plugin main file founded: ' . $plugin_main_file['filename'] . PHP_EOL );
echo( 'Starting obfuscating......' . PHP_EOL . PHP_EOL );
exec( "php yakpro-po/yakpro-po.php {$directory} -o output" );
create_zip( dirname( __FILE__ ) . '/output/yakpro-po/obfuscated', $directory_base_name, $plugin_main_file, $removed_items );
exec( 'rm -rf output/yakpro-po' );


function check_is_main_file_found( $file ) {
	if ( ! isset( $file['filename'] ) || empty( $file ) ) {
		echo 'Error - cant detect plugin main file' . PHP_EOL;
		exit();
	}
}

function is_file_supported( $file, $supported_types ): bool {
	$file_type = pathinfo( $file, PATHINFO_EXTENSION );

	return ! $file->isDir() && in_array( strtolower( $file_type ), $supported_types );
}

function get_first_comment_of_file( $file_name ) {
	$docComments    = array_filter(
		token_get_all( file_get_contents( $file_name ) ), function ( $entry ) {
		return $entry[0] == T_DOC_COMMENT;
	} );
	$fileDocComment = array_shift( $docComments );

	return $fileDocComment[1] ?? null;
}

function check_is_directory_correct( $directory ) {
	if ( ! $directory || ! is_dir( $directory ) ) {
		echo 'Error - Invalid Directory!' . PHP_EOL;
		exit();
	}
}

function is_plugin_main_file( $comment ): bool {
	return strpos( strtolower( $comment ), 'plugin name' ) != false && strpos( strtolower( $comment ), 'version' ) != false;
}

function create_zip( $directory, $parent_folder_name, $plugin_main_file, $exclude ): void {

	$root_path = realpath( $directory );
	$file_name = 'output/' . ( new DateTime() )->format( "Y-m-d-H-i-s" ) . '.zip';
	$zip       = new ZipArchive();

	$zip->open( $file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE );
	$zip->addEmptyDir( $parent_folder_name );

	/** @var SplFileInfo[] $files */
	$files = generate_zip_iterator($root_path, $exclude);

	foreach ( $files as $file ) {
		if ( $file->getFilename() === $plugin_main_file['filename'] ) {
			$content    = file( $file->getPathname() );
			$first_line = array_shift( $content );
			array_unshift( $content, $first_line . PHP_EOL, $plugin_main_file['plugin_info_comment'] . PHP_EOL );
			file_put_contents( $file->getPathname(), $content );
		}

		$filePath     = $file->getRealPath();
		$relativePath = substr( $filePath, strlen( $root_path ) + 1 );
		$zip->addFile( $filePath, $parent_folder_name . "/" . $relativePath );
	}

	$zip->close();
}

function generate_zip_iterator( $root_path, $exclude ): RecursiveIteratorIterator {
	$filter = function ( $file, $key, $iterator ) use ( $exclude ) {
		if ( !in_array( $file->getFilename(), $exclude ) || !in_array( $file->getFilename(), $exclude ) && $iterator->hasChildren() ) {
			return true;
		}

		return $file->isFile() && !in_array( $file->getFilename(), $exclude );
	};

	$innerIterator = new RecursiveDirectoryIterator(
		$root_path,
		RecursiveDirectoryIterator::SKIP_DOTS
	);

	return new RecursiveIteratorIterator(
		new RecursiveCallbackFilterIterator( $innerIterator, $filter )
	);
}

