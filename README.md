# Bi root. Default Helpers, Actions, Classes and Config for BI framework


Refrer to https://github.com/rozdol/bi-skel

## Test API

```bash
curl -H "Content-Type: application/json" -H "Accept: application/json" -X POST -d '{"user":"admin","pass":"Pass1234"}' http://dev-bi.lan/\?act\=api

curl -H "Content-Type: application/json" -H "Accept: application/json" -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwidW5tIjoiYWRtaW4iLCJleHAiOjE1MzE2MDYzODZ9.73ZF8oll8YjxoV2tbvRJS5b4AhH-0m1ZnJOy7ClOBEM" -X POST -d '{"user":"admin", "api_key":"89af0c-fca3fb-d19cd1-017216-38bd18", "func":"get_rate"}' http://dev-bi.lan/\?act\=api
```