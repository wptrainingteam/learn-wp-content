#!/bin/bash

# Loop through all files in the current directory
for file in *; do
    # If the file has a .srt extension
    if [[ $file == *.srt ]]; then
        # Convert the file to .ttml using tt convert -i inputfile.srt -o outputfile.ttml
        tt convert -i "$file" -o "${file%.*}".ttml
    fi
done