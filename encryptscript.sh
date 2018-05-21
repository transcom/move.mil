#!/bin/bash
 # Converts the discount file to .csv and encrypts it.

source .env


echo What is the absolute path of the file?
read filename

while [[ "$response" != "y" ]] ; do
 echo What is the effective date of the discounts?
 read date
 echo Is $date correct? \(y/N\)
 read response
done

if [[ -z ${SEEDS_ENC_IV+x} ]]; then
 echo "SEEDS_ENC isnt set"
 exit 1 
else 
 echo "SEEDS_ENC is set to '$var'" 
fi

if [[ -z ${SEEDS_ENC+x} ]]; then
 echo "INC isnt set" 
 exit 1 
else 
 echo "INC is set to '$var'" 
fi

if [[ $date =~ ^[ADFJMNOS][aceopu][bcglnprtvy]-\d{2}-\d{4}$ ]]; then
 echo Converting $filename.....

 if [[ command -v gnumeric >/dev/null 2>&1 ]]; then
  ssconvert $filename.xlsx discount-$date.csv
  openssl enc -e -aes-256-cbc -iv $SEEDS_ENC_IV -K $SEEDS_ENC -in discount-$date.csv -out lib/data/discounts-$date.csv.enc
 else
  echo Command gnumeric not installed.
  echo Try 'brew install' or look up a tutorial on google for your appropriate OS.
 fi
else
 echo Date has incorrect syntax.
fi
