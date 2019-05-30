#!/usr/bin/env bash
echo "Setting AWS variables"
key_id_regex='"([A-Z0-9]+)",'
secret_regex='"SecretAccessKey": "([A-Za-z0-9\/\+]+)",'
token_regex='"SessionToken": "(.+)",'

session_token=$(aws sts get-session-token --profile=movemil)

[[ $session_token =~ $key_id_regex ]] && key_id="${BASH_REMATCH[1]}"

[[ $session_token =~ $secret_regex ]] && secret="${BASH_REMATCH[1]}"

[[ $session_token =~ $token_regex ]] && token="${BASH_REMATCH[1]}"

export AWS_ACCESS_KEY_ID=$key_id
export AWS_SECRET_ACCESS_KEY=$secret
export AWS_SESSION_TOKEN=$token

echo "Login to docker"
login=$(aws ecr get-login --no-include-email)
eval $login

echo "Build docker image"
docker build -t movemil -f docker/Dockerfile .

echo "Tag"
docker tag movemil:latest 328180890751.dkr.ecr.us-east-1.amazonaws.com/movemil:test

echo "Deploy"
docker push 328180890751.dkr.ecr.us-east-1.amazonaws.com/movemil:test