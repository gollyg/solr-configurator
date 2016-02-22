# Overview
This is a very simple utility to convert CSV files into Solr configuration files.

Currently supports the synonym text format, with options for implicit/explicit formats.

# Usage
Run from the repo root:
```
 php run.php solr:synonym source_file [destination_file]
```

## Options
```
--implicit -i : Use implicit file syntax
--no-header -H : Source file does not contain a header row
```