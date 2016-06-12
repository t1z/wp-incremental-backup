<?php
require 'class-t1z-wpib-exception.php';
define('FILES_TO_DELETE', '__deleted_files__.txt');
class T1z_Incremental_Backup_Deleted_Walker {

    private $input_dir;
    private $output_dir;
    private $output_list_txt;
    public function __construct($output_txt, $input_dir) {
        $this->output_list_txt = $output_txt;
        $this->output_dir = dirname($output_txt);
        $this->output_list_csv = $this->output_dir . '/list.csv';
        $this->input_dir = $input_dir;
        $this->read();
    }

    /**
     * Read last file list
     */
    public function read() {
        $this->fh = fopen($this->output_list_csv, "r");
        do {
            $line_read = fgetcsv($this->fh);
            if (is_null($line_read)) {
                throw new Exception("invalid handle, aborting");
            }
            $name = $line_read[0];
            $md5 = $line_read[1];
            $this->files[$name] = $md5;
        } while($line_read !== false);
    }

    /**
     * Get md5 from existing file
     */
    private function get_md5($name) {
        return $this->files[$name];
    }


    /**
     * Check if file is a special dir: either . or ..
     */
    private function is_special_dir($object) {
        return $object->getFilename() === '.' || $object->getFilename() === '..';
    }

    /**
     * Check if file is the output dir
     */
    private function is_output_dir($object) {
        return dirname($object->getPathname()) === $this->output_dir;
    }

    /**
     * Check if file is a regular file
     */
    private function is_regular_file($object) {
        return !is_dir($object->getPathname());
    }

    /**
     * Recurse wp installation
     */
    public function prepare_files_archive() {
        // echo "prepare start: " . $this->current_time_diff() . "<br>";
        $found_in_dirs = [];
        $files_to_delete = [];

        // $this->fh = fopen($this->output_list_csv, "w");
        // if($this->fh === false) {
        //     throw new T1z_WPIB_Exception("Could not open output CSV file in write mode: {$this->output_list_csv}", T1z_WPIB_Exception::FILES);
        // }

        $objects = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->input_dir),
            RecursiveIteratorIterator::SELF_FIRST
        );

        // Iterate directory
        foreach($objects as $name => $object) {

            // Skip deleted files list => delete it
            if($object->getFilename() === FILES_TO_DELETE) {
                unlink($object->getPathname());
                continue;
            }

            // Skip if this is . or .. or output dir 
            if($this->is_special_dir($object) || $this->is_output_dir($object)) {
                continue;
            }

            // Add dir
            if(!$this->is_regular_file($object)) {
                continue;
            }
            $found_in_dirs[] = $name;
        }

        // Iterate existing files from previous backup's list
        foreach($this->files as $name => $md5) {
            if (!empty($md5) && array_search($name, $found_in_dirs) === false) {
                // echo "<em>deleted</em> file: $name<br>";
                $files_to_delete[] = $name;
            }
        }
        // $deleted_list_file = $this->write_files_to_delete($files_to_delete);
        // if (!empty($deleted_list_file)) {
        //     $files_to_archive[] = $deleted_list_file;
        // }
        // echo "prepare end: " . $this->current_time_diff() . "<br>";


        // $this->write_tar_archive($files_to_archive);
        // fclose($this->fh);

        // $this->write_archive_list($files_to_archive);

        // Log what whas done
        // $this->log([
        //     'new'      => $files_new,
        //     'modified' => array_keys($files_modified),
        //     'deleted'  => $files_to_delete
        // ]);

        // return [
        //     'new'      => $files_new,
        //     'modified' => $files_modified,
        //     'deleted'  => $files_to_delete
        // ];
    }
}
