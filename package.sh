#! /bin/sh

echo "Plugin Version? (ie: 1.2.0), followed by [ENTER]:"

read TAG

# Use the physical directory - not symlinked dir
cd -P ../
tar -zcvf kindle-feed-$TAG.tar.gz --exclude='.git' --exclude='difflist' --exclude='*.md' --exclude='*.log' --exclude='*.tar.gz' kindle-feed
mv kindle-feed-$TAG.tar.gz ./kindle-feed/
cd kindle-feed
exit
