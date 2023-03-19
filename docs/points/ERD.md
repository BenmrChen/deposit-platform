# Points ERD

## Q
1. tables是否應帶 module ?
2. point_client 與 oauth_clients 是否應該分開？
3. balances是否放一起？
   
```mermaid
  erDiagram
    settings {
      int id PK
      string group
      string key
      string value
    }

    users {
      uuid id PK
    }

    user_balances {
      int id PK
      uuid user_id
      string symbol
      decimal balance
    }

    user_balance_logs {
      int id PK
      int user_balance_id
      uuid user_id
      uuid client_id
      string symbol
      decimal amount_delta
      decimal balance
      string ref_subject
      string ref_id
      tinyint type "deposit / withdraw"
    }

    oauth_clients {
      uuid id PK
      string redirect_uri
      bool deposit_enabled
      bool withdraw_enabled
      bool ip_allowlist_enabled
      text point_api_secret
      json symbols
      json callback_urls
    }

    client_balances {
      int id PK
      uuid client_id
      string symbol
      decimal balance
    }

    client_balance_logs {
      int id PK
      int client_balance_id
      uuid client_id
      string symbol
      tinyint type "deposit / withdraw"
      decimal amount_delta
      decimal balance
      string ref_subject
      string ref_id
    }

    client_ips {
      int id PK
      uuid client_id
      string ip
      string note
    }

    orders {
      int id PK "snowflake id"
      uuid client_id
      uuid user_id
      string ref_order_id "client order id"
      string symbol
      string product_name
      tinyint type "deposit / withdraw"
      decimal amount
      tinyint status
    }

    client_user_maps {
      uuid user_id
      uuid client_id
      string ref_user_id "client user id"
    }

    client_top_ups {
      int id PK
      uuid client_id
      string symbol
      tinyint type
      decimal amount
      string note "required"
    }

    order_notifications {
      int id PK
      uuid client_id
      bigint order_id
      json payload
      tinyint status
      tinyint retries
      datetime last_retried_at
      json response
    }

    cybavo_wallets {
      int id PK
      string wallet_id "unique"
      string symbol
      string type
      int parent_id
      tinyint category
      tinyint is_enabled
      string api_key
      text api_secret
      string refresh_token
      string withdrawal_prefix
      datetime expired_at
    }

    user_withdrawals {
      bigint id PK "snowflake id"
      uuid user_id
      uuid client_id
      string symbol
      string chain_type
      string tx_id
      tinyint status
      tinyint chain_status
      decimal amount
      decimal fee
      decimal total_amount
      string from_address
      string to_address
      string ref_id
      string ref_wallet_id
      tinyint cancelled_by
      datetime submitted_at
      datetime confirmed_at
    }

    
    users ||--o{ user_balances : ""

    client_user_maps ||--o{ users : ""
    client_user_maps ||--o{ clients : ""

    user_balances ||--o{ user_balance_logs : ""
    
    clients ||--o{ client_balances : ""
    client_balances ||--o{ client_balance_logs : ""
    client_balance_logs ||--o| client_top_ups : "" 
    client_balance_logs ||--o| client_withdrawals : "" 
    
    clients ||--o{ client_ips : has
    clients ||--o{ orders : places
    orders ||--o| order_notifications : ""

    client_balance_logs ||--o{ orders : "" 
    user_balance_logs ||--o| orders : "" 
    user_balance_logs ||--o| user_withdrawals : "" 
    users ||--o{ user_withdrawals : "applies for"

```

## 重要狀態 / 類型列表


### tbl.orders

| 用途 | 欄位 | 常數 | 紀錄 | 說明 |
| --- | --- | --- | --- | --- |
| 訂單狀態 | status | NEW | 0 | [`default`] 新建立 |
||| COMPLETE | 1 | 訂單完成 |
||| CANCELLED | 10 | 取消 |
