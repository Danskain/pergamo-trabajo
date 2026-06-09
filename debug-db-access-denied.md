# Debug Session: db-access-denied
- **Status**: [OPEN]
- **Issue**: Laravel returns `SQLSTATE[HY000] [1045] Access denied for user 'laravel'` when querying MySQL at host `mysql`.
- **Debug Server**: not-started
- **Log File**: .dbg/trae-debug-log-db-access-denied.ndjson

## Reproduction Steps
1. Start the stack with `docker compose up -d`.
2. Query an endpoint that reads from MySQL, such as the months endpoint.
3. Observe `QueryException` with MySQL error `1045`.

## Hypotheses & Verification
| ID | Hypothesis | Likelihood | Effort | Evidence |
|----|------------|------------|--------|----------|
| A | The MySQL volume was initialized earlier with different credentials, so `MYSQL_USER` and `MYSQL_PASSWORD` in Compose are no longer being applied. | High | Low | Pending |
| B | The `laravel` user exists but does not have privileges for connections from the Docker network host. | High | Low | Pending |
| C | Laravel is reading different DB credentials than the current `.env` values due to cached config or env mismatch. | Medium | Low | Pending |
| D | The endpoint is reaching a different MySQL container or database instance than expected. | Low | Medium | Pending |

## Log Evidence
- Pending

## Verification Conclusion
- Pending
