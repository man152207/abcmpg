# Production Database Monitoring — Setup Guide

Monitor the live app's database connection automatically using the free
[UptimeRobot](https://uptimerobot.com) service. If the database goes down,
UptimeRobot will send an email (or SMS) alert within 5 minutes.

---

## Health Endpoint

| | |
|---|---|
| **URL** | `https://abcmpg.replit.app/health` |
| **Method** | GET |
| **Auth** | None (public) |

**Healthy response (HTTP 200)**
```json
{"status":"ok","db_ok":true,"driver":"mysql","db_name":"mpgcomnp_wp146"}
```

**Unhealthy response (HTTP 503)**
```json
{"status":"error","db_ok":false,"driver":"mysql","error":"database_unreachable"}
```

---

## UptimeRobot Setup (Free — 50 monitors, 5-minute checks)

### Step 1 — Create a free account
1. Go to [https://uptimerobot.com](https://uptimerobot.com) and click **Register for FREE**.
2. Verify your email address.

### Step 2 — Add a new monitor
1. Click **+ Add New Monitor**.
2. Fill in the form:
   | Field | Value |
   |---|---|
   | Monitor Type | **HTTPS** |
   | Friendly Name | `MPG Production DB` |
   | URL | `https://abcmpg.replit.app/health` |
   | Monitoring Interval | **5 minutes** |
3. Expand **Advanced Settings** → enable **"Monitor Should Contain Keyword"**
   - Keyword: `"db_ok":true`
   - Alert when **keyword not found** (this catches the case where the server
     returns HTTP 200 but the database is actually down).
4. Click **Create Monitor**.

### Step 3 — Configure alert contacts
1. In the left menu go to **My Settings → Alert Contacts**.
2. Click **Add Alert Contact**:
   - Type: **E-mail**
   - Friendly Name: `MPG Admin`
   - E-mail: *(your email address)*
3. Save, then go back to the monitor and assign this contact under
   **Alert Contacts to Notify**.

### Step 4 — Test it
- Click **Details** on the monitor. Status should show **Up (OK)** in green.
- To test the alert path, temporarily change the keyword to something that
  won't match (e.g. `db_ok_FAKE`), wait 5 min — you should receive an email.
  Revert the keyword afterward.

---

## Alternative Free Services

| Service | Free tier | Check interval |
|---|---|---|
| [UptimeRobot](https://uptimerobot.com) | 50 monitors | 5 min |
| [Better Uptime](https://betteruptime.com) | 10 monitors | 3 min |
| [Freshping](https://freshping.io) | 50 monitors | 1 min |

---

## What triggers an alert?

An alert fires if **either** condition is true:

1. The server returns **HTTP 503** (database is unreachable).
2. The server returns HTTP 200 but the keyword `"db_ok":true` is **missing**
   from the response body (database connected but an unexpected error occurred).

Normal operations (login, ads, reception pages) are **not** affected — the
`/health` endpoint is a read-only probe that runs independently.
