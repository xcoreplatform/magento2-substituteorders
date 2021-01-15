# Dealer4Dealer Substitute Orders

Replace default Magento orders in frontend. So orders from an external source will also be displayed in the grid.
Substitute orders support:

- Orders
    - Additional data
    - Shipping address
        - Additional data
    - Billing address
        - Additional data
    - items
        - Additional data
- Invoices
    - Additional data
    - Shipping address
        - Additional data
    - Billing address
        - Additional data
    - items
        - Additional data
- Shipments
    - Multiple trackers.
    - Additional data
    - Shipping address
        - Additional data
    - Billing address
        - Additional data
    - items
        - Additional data


# Events hooked in

- sales_order_place_after
- sales_order_invoice_save_after
- sales_order_shipment_save_after

# API
The substitute order module uses the Magento 2 REST API for CRUD operations on Order, Invoice and Shipment.

## Orders
### Json data structure
    {
        "order_id": "string",
        "invoice_ids": [
            0
        ],
        "magento_order_id": "string",
        "magento_customer_id": "string",
        "external_customer_id": "string",
        "ext_order_id": "string",
        "base_grandtotal": "string",
        "base_subtotal": "string",
        "grandtotal": "string",
        "subtotal": "string",
        "po_number": "string",
        "state": "string",
        "shipping_method": "string",
        "shipping_address": {
          "orderaddress_id": "string",
          "firstname": "string",
          "middlename": "string",
          "lastname": "string",
          "prefix": "string",
          "suffix": "string",
          "company": "string",
          "street": "string",
          "postcode": "string",
          "city": "string",
          "country": "string",
          "telephone": "string",
          "fax": "string",
          "additional_data": [
            {
              "key": "string",
              "value": "string"
            }
          ]
        },
        "billing_address": {
          "orderaddress_id": "string",
          "firstname": "string",
          "middlename": "string",
          "lastname": "string",
          "prefix": "string",
          "suffix": "string",
          "company": "string",
          "street": "string",
          "postcode": "string",
          "city": "string",
          "country": "string",
          "telephone": "string",
          "fax": "string",
          "additional_data": [
            {
              "key": "string",
              "value": "string"
            }
          ]
        },
        "payment_method": "string",
        "base_discount_amount": "string",
        "discount_amount": "string",
        "order_date": "string",
        "base_tax_amount": "string",
        "tax_amount": "string",
        "base_shipping_amount": "string",
        "shipping_amount": "string",
        "items": [
          {
            "orderitem_id": "string",
            "order": "string",
            "order_id": "string",
            "invoice_id": "string",
            "name": "string",
            "sku": "string",
            "base_price": "string",
            "price": "string",
            "base_row_total": "string",
            "row_total": "string",
            "base_tax_amount": "string",
            "tax_amount": "string",
            "qty": "string",
            "additional_data": [
              {
                "key": "string",
                "value": "string"
              }
            ],
            "base_discount_amount": "string",
            "discount_amount": "string"
          }
        ],
        "magento_increment_id": "string",
        "updated_at": "string",
        "additional_data": [
          {
            "key": "string",
            "value": "string"
          }
        ],
        "attachments": [
          {
            "file_data": "string",
            "name": "string"
          }
        ]
    }

#### Create order
-   **URL:**
    /V1/dealer4dealer-substituteorders/order
-   **Method:**:
    `POST`
-   **URL Params**
    None
-   **Data params**
    ```json
    { 
        "order": <order object> 
    }
    ```
-   **Success Response:**
    - **Code:** 200
    - **Content:** <Order ID>

#### Update order
-   **URL:**
    /V1/dealer4dealer-substituteorders/order
-   **Method:**:
    `PUT`
-   **URL Params**
    None
-   **Data params**
    ```json
    { 
        "order": <order object> 
    }
    ```
-   **Success Response:**
    - **Code:** 200
    - **Content:** <Order ID>

#### Delete Order
-   **URL:**
    /V1/dealer4dealer-substituteorders/order/{id}
-   **Method:**:
    `DELETE`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** boolean

#### Get Order
-   **URL:**
    /V1/dealer4dealer-substituteorders/order/{id}
-   **Method:**:
    `GET`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** <order object>
    
#### Get Order by magento_order_id
-   **URL:**
    /V1/dealer4dealer-substituteorders/order/magentoorder/{id}
-   **Method:**:
    `GET`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** <order object>    

#### Get Order by ext_order_id
-   **URL:**
    /V1/dealer4dealer-substituteorders/order/extorder/{id}
-   **Method:**:
    `GET`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** <order object>
    
## Invoices
### Json data structure
    {
        "invoice_id": "string",
        "order_ids": [
            0
        ],
        "magento_invoice_id": "string",
        "ext_invoice_id": "string",
        "po_number": "string",
        "magento_customer_id": "string",
        "base_tax_amount": "string",
        "base_discount_amount": "string",
        "base_shipping_amount": "string",
        "base_subtotal": "string",
        "base_grandtotal": "string",
        "tax_amount": "string",
        "discount_amount": "string",
        "shipping_amount": "string",
        "subtotal": "string",
        "grandtotal": "string",
        "invoice_date": "string",
        "state": "string",
        "magento_increment_id": "string",
        "additional_data": [
          {
            "key": "string",
            "value": "string"
          }
        ],
        "items": [
          {
            "invoiceitem_id": "string",
            "invoice_id": "string",
            "order_id": "string",
            "name": "string",
            "sku": "string",
            "base_price": "string",
            "price": "string",
            "base_row_total": "string",
            "row_total": "string",
            "base_tax_amount": "string",
            "tax_amount": "string",
            "qty": "string",
            "additional_data": [
              {
                "key": "string",
                "value": "string"
              }
            ],
            "base_discount_amount": "string",
            "discount_amount": "string"
          }
        ],
        "shipping_address": {
          "orderaddress_id": "string",
          "firstname": "string",
          "middlename": "string",
          "lastname": "string",
          "prefix": "string",
          "suffix": "string",
          "company": "string",
          "street": "string",
          "postcode": "string",
          "city": "string",
          "country": "string",
          "telephone": "string",
          "fax": "string",
          "additional_data": [
            {
              "key": "string",
              "value": "string"
            }
          ]
        },
        "billing_address": {
          "orderaddress_id": "string",
          "firstname": "string",
          "middlename": "string",
          "lastname": "string",
          "prefix": "string",
          "suffix": "string",
          "company": "string",
          "street": "string",
          "postcode": "string",
          "city": "string",
          "country": "string",
          "telephone": "string",
          "fax": "string",
          "additional_data": [
            {
              "key": "string",
              "value": "string"
            }
          ],
          "attachments": [
             {
               "file_data": "string",
               "name": "string"
             }
          ]
        }
    }
 
#### Create invoice
-   **URL:**
    /V1/dealer4dealer-substituteorders/invoice
-   **Method:**:
    `POST`
-   **URL Params**
    None
-   **Data params**
    ```json
    { 
        "invoice": <invoice object> 
    }
    ```
-   **Success Response:**
    - **Code:** 200
    - **Content:** <Invoice ID>

#### Update invoice
-   **URL:**
    /V1/dealer4dealer-substituteorders/invoice
-   **Method:**:
    `PUT`
-   **URL Params**
    None
-   **Data params**
    ```json
    { 
        "invoice": <Invoice object> 
    }
    ```
-   **Success Response:**
    - **Code:** 200
    - **Content:** <Invoice ID>

#### Delete invoice
-   **URL:**
    /V1/dealer4dealer-substituteorders/invoice/{id}
-   **Method:**:
    `DELETE`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** boolean

#### Get invoice
-   **URL:**
    /V1/dealer4dealer-substituteorders/invoice/{id}
-   **Method:**:
    `GET`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** <invoice object>

## Shipments
### Json data structure
    {
        "shipment_id": "string",
        "ext_shipment_id": "string",
        "customer_id": "string",
        "order_id": "string",
        "invoice_id": "string",
        "shipment_status": "string",
        "increment_id": "string",
        "created_at": "string",
        "updated_at": "string",
        "name": "string",
        "tracking": [
          {
            "carrier_name": "string",
            "code": "string",
            "url": "string"
          }
        ],
        "additional_data": [
          {
            "key": "string",
            "value": "string"
          }
        ],
        "attachments": [
           {
             "file_data": "string",
             "name": "string"
           }
        ],
        "items": [
          {
            "shipmentitem_id": "string",
            "shipment_id": "string",
            "row_total": "string",
            "price": "string",
            "weight": "string",
            "qty": "string",
            "sku": "string",
            "name": "string",
            "description": "string",
            "additional_data": [
              {
                "key": "string",
                "value": "string"
              }
            ]
          }
        ],
        "shipping_address": {
          "orderaddress_id": "string",
          "firstname": "string",
          "middlename": "string",
          "lastname": "string",
          "prefix": "string",
          "suffix": "string",
          "company": "string",
          "street": "string",
          "postcode": "string",
          "city": "string",
          "country": "string",
          "telephone": "string",
          "fax": "string",
          "additional_data": [
            {
              "key": "string",
              "value": "string"
            }
          ]
        },
        "billing_address": {
          "orderaddress_id": "string",
          "firstname": "string",
          "middlename": "string",
          "lastname": "string",
          "prefix": "string",
          "suffix": "string",
          "company": "string",
          "street": "string",
          "postcode": "string",
          "city": "string",
          "country": "string",
          "telephone": "string",
          "fax": "string",
          "additional_data": [
            {
              "key": "string",
              "value": "string"
            }
          ]
        }
    }  
 
#### Create shipment
-   **URL:**
    /V1/dealer4dealer-substituteorders/shipment
-   **Method:**:
    `POST`
-   **URL Params**
    None
-   **Data params**
    ```json
    { 
        "shipment": <shipment object> 
    }
    ```
-   **Success Response:**
    - **Code:** 200
    - **Content:** <Invoice ID>

#### Update shipment
-   **URL:**
    /V1/dealer4dealer-substituteorders/shipment
-   **Method:**:
    `PUT`
-   **URL Params**
    None
-   **Data params**
    ```json
    { 
        "shipment": <shipment object> 
    }
    ```
-   **Success Response:**
    - **Code:** 200
    - **Content:** <shipment ID>

#### Delete shipment
-   **URL:**
    /V1/dealer4dealer-substituteorders/shipment/{id}
-   **Method:**:
    `DELETE`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** boolean

#### Get shipment
-   **URL:**
    /V1/dealer4dealer-substituteorders/shipment/{id}
-   **Method:**:
    `GET`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** <shipment object>

## Attachments

### Json data structure

```json
{
  "attachment_id": "string",
  "magento_customer_identifier": "string",
  "file": "string",
  "entity_type": "string",
  "entity_type_identifier": "string",
  "file_content": {
    "file_data": "string",
    "name": "string"
  }
}
```

#### Create Attachment
-   **URL:**
    /V1/dealer4dealer-substituteorders/attachment
-   **Method:**:
    `POST`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    ```json
        { 
            "attachment": <attachment object> 
        }
    ```
-   **Success Response:**
    - **Code:** 200
    - **Content:** boolean


#### Update Attachment
-   **URL:**
    /V1/dealer4dealer-substituteorders/attachment/{id}
-   **Method:**:
    `PUT`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    ```json
        { 
            "attachment": <attachment object> 
        }
    ```
-   **Success Response:**
    - **Code:** 200
    - **Content:** boolean


#### Delete Attachment
-   **URL:**
    /V1/dealer4dealer-substituteorders/attachment/{id}
-   **Method:**:
    `DELETE`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** boolean

#### Get Attachment
-   **URL:**
    /V1/dealer4dealer-substituteorders/attachment/{id}
-   **Method:**:
    `GET`
-   **URL Params**
    id=[integer] *(required)*
-   **Data params**
    None
-   **Success Response:**
    - **Code:** 200
    - **Content:** boolean


## Examples


### Attachments

Base64encoded of an test PDF file

```
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agooRDoyMDE3MTIyOTA4NDkzMVowMCcwMCcpCmVuZG9iagoyMiAwIG9iagooKQplbmRvYmoKMjMgMCBvYmoKWyBdCmVuZG9iagoxIDAgb2JqCjw8IC9UaXRsZSAxOCAwIFIgL1Byb2R1Y2VyIDE5IDAgUiAvQ3JlYXRvciAyMCAwIFIgL0NyZWF0aW9uRGF0ZSAyMSAwIFIgL01vZERhdGUKMjEgMCBSIC9LZXl3b3JkcyAyMiAwIFIgL0FBUEw6S2V5d29yZHMgMjMgMCBSID4+CmVuZG9iagp4cmVmCjAgMjQKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDA2Mzc0IDAwMDAwIG4gCjAwMDAwMDAzMzkgMDAwMDAgbiAKMDAwMDAwMzMyMiAwMDAwMCBuIAowMDAwMDAwMDIyIDAwMDAwIG4gCjAwMDAwMDAzMjAgMDAwMDAgbiAKMDAwMDAwMDQ1MyAwMDAwMCBuIAowMDAwMDAzMjg2IDAwMDAwIG4gCjAwMDAwMDAwMDAgMDAwMDAgbiAKMDAwMDAwMzQ2NSAwMDAwMCBuIAowMDAwMDAwNTUwIDAwMDAwIG4gCjAwMDAwMDMyNjUgMDAwMDAgbiAKMDAwMDAwMzQxNSAwMDAwMCBuIAowMDAwMDA0MDg0IDAwMDAwIG4gCjAwMDAwMDM2NzkgMDAwMDAgbiAKMDAwMDAwNDA2NCAwMDAwMCBuIAowMDAwMDA0MzIwIDAwMDAwIG4gCjAwMDAwMDYxNTEgMDAwMDAgbiAKMDAwMDAwNjE3MiAwMDAwMCBuIAowMDAwMDA2MjE3IDAwMDAwIG4gCjAwMDAwMDYyNzAgMDAwMDAgbiAKMDAwMDAwNjI5MyAwMDAwMCBuIAowMDAwMDA2MzM1IDAwMDAwIG4gCjAwMDAwMDYzNTQgMDAwMDAgbiAKdHJhaWxlcgo8PCAvU2l6ZSAyNCAvUm9vdCAxMiAwIFIgL0luZm8gMSAwIFIgL0lEIFsgPGNlNmU4ZjM2NTU4Y2E3YTg5OGQxZmM0MGRjYmZkZDA4Pgo8Y2U2ZThmMzY1NThjYTdhODk4ZDFmYzQwZGNiZmRkMDg+IF0gPj4Kc3RhcnR4cmVmCjY1MTgKJSVFT0YK
```

#### Example Call - POST Add Single Attachment

```POST /V1/dealer4dealer-substituteorders/attachment```

```json

{
  "attachment": {
    "magento_customer_identifier": "1",
    "entity_type": "order",
    "entity_type_identifier": "1",
    "file_content": {
      "file_data": "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agooRDoyMDE3MTIyOTA4NDkzMVowMCcwMCcpCmVuZG9iagoyMiAwIG9iagooKQplbmRvYmoKMjMgMCBvYmoKWyBdCmVuZG9iagoxIDAgb2JqCjw8IC9UaXRsZSAxOCAwIFIgL1Byb2R1Y2VyIDE5IDAgUiAvQ3JlYXRvciAyMCAwIFIgL0NyZWF0aW9uRGF0ZSAyMSAwIFIgL01vZERhdGUKMjEgMCBSIC9LZXl3b3JkcyAyMiAwIFIgL0FBUEw6S2V5d29yZHMgMjMgMCBSID4+CmVuZG9iagp4cmVmCjAgMjQKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDA2Mzc0IDAwMDAwIG4gCjAwMDAwMDAzMzkgMDAwMDAgbiAKMDAwMDAwMzMyMiAwMDAwMCBuIAowMDAwMDAwMDIyIDAwMDAwIG4gCjAwMDAwMDAzMjAgMDAwMDAgbiAKMDAwMDAwMDQ1MyAwMDAwMCBuIAowMDAwMDAzMjg2IDAwMDAwIG4gCjAwMDAwMDAwMDAgMDAwMDAgbiAKMDAwMDAwMzQ2NSAwMDAwMCBuIAowMDAwMDAwNTUwIDAwMDAwIG4gCjAwMDAwMDMyNjUgMDAwMDAgbiAKMDAwMDAwMzQxNSAwMDAwMCBuIAowMDAwMDA0MDg0IDAwMDAwIG4gCjAwMDAwMDM2NzkgMDAwMDAgbiAKMDAwMDAwNDA2NCAwMDAwMCBuIAowMDAwMDA0MzIwIDAwMDAwIG4gCjAwMDAwMDYxNTEgMDAwMDAgbiAKMDAwMDAwNjE3MiAwMDAwMCBuIAowMDAwMDA2MjE3IDAwMDAwIG4gCjAwMDAwMDYyNzAgMDAwMDAgbiAKMDAwMDAwNjI5MyAwMDAwMCBuIAowMDAwMDA2MzM1IDAwMDAwIG4gCjAwMDAwMDYzNTQgMDAwMDAgbiAKdHJhaWxlcgo8PCAvU2l6ZSAyNCAvUm9vdCAxMiAwIFIgL0luZm8gMSAwIFIgL0lEIFsgPGNlNmU4ZjM2NTU4Y2E3YTg5OGQxZmM0MGRjYmZkZDA4Pgo8Y2U2ZThmMzY1NThjYTdhODk4ZDFmYzQwZGNiZmRkMDg+IF0gPj4Kc3RhcnR4cmVmCjY1MTgKJSVFT0YK",
      "name": "test.pdf"
    }
  }
}

```

#### Example Call - PUT Add attachments to order

```PUT /V1/dealer4dealer-substituteorders/order```
        
```json

{
  "order": {
    "order_id": "1",
    "attachments": [{
      "file_data": "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agooRDoyMDE3MTIyOTA4NDkzMVowMCcwMCcpCmVuZG9iagoyMiAwIG9iagooKQplbmRvYmoKMjMgMCBvYmoKWyBdCmVuZG9iagoxIDAgb2JqCjw8IC9UaXRsZSAxOCAwIFIgL1Byb2R1Y2VyIDE5IDAgUiAvQ3JlYXRvciAyMCAwIFIgL0NyZWF0aW9uRGF0ZSAyMSAwIFIgL01vZERhdGUKMjEgMCBSIC9LZXl3b3JkcyAyMiAwIFIgL0FBUEw6S2V5d29yZHMgMjMgMCBSID4+CmVuZG9iagp4cmVmCjAgMjQKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDA2Mzc0IDAwMDAwIG4gCjAwMDAwMDAzMzkgMDAwMDAgbiAKMDAwMDAwMzMyMiAwMDAwMCBuIAowMDAwMDAwMDIyIDAwMDAwIG4gCjAwMDAwMDAzMjAgMDAwMDAgbiAKMDAwMDAwMDQ1MyAwMDAwMCBuIAowMDAwMDAzMjg2IDAwMDAwIG4gCjAwMDAwMDAwMDAgMDAwMDAgbiAKMDAwMDAwMzQ2NSAwMDAwMCBuIAowMDAwMDAwNTUwIDAwMDAwIG4gCjAwMDAwMDMyNjUgMDAwMDAgbiAKMDAwMDAwMzQxNSAwMDAwMCBuIAowMDAwMDA0MDg0IDAwMDAwIG4gCjAwMDAwMDM2NzkgMDAwMDAgbiAKMDAwMDAwNDA2NCAwMDAwMCBuIAowMDAwMDA0MzIwIDAwMDAwIG4gCjAwMDAwMDYxNTEgMDAwMDAgbiAKMDAwMDAwNjE3MiAwMDAwMCBuIAowMDAwMDA2MjE3IDAwMDAwIG4gCjAwMDAwMDYyNzAgMDAwMDAgbiAKMDAwMDAwNjI5MyAwMDAwMCBuIAowMDAwMDA2MzM1IDAwMDAwIG4gCjAwMDAwMDYzNTQgMDAwMDAgbiAKdHJhaWxlcgo8PCAvU2l6ZSAyNCAvUm9vdCAxMiAwIFIgL0luZm8gMSAwIFIgL0lEIFsgPGNlNmU4ZjM2NTU4Y2E3YTg5OGQxZmM0MGRjYmZkZDA4Pgo8Y2U2ZThmMzY1NThjYTdhODk4ZDFmYzQwZGNiZmRkMDg+IF0gPj4Kc3RhcnR4cmVmCjY1MTgKJSVFT0YK",
      "name": "attachment1.pdf"
    },
    {
      "file_data": "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agooRDoyMDE3MTIyOTA4NDkzMVowMCcwMCcpCmVuZG9iagoyMiAwIG9iagooKQplbmRvYmoKMjMgMCBvYmoKWyBdCmVuZG9iagoxIDAgb2JqCjw8IC9UaXRsZSAxOCAwIFIgL1Byb2R1Y2VyIDE5IDAgUiAvQ3JlYXRvciAyMCAwIFIgL0NyZWF0aW9uRGF0ZSAyMSAwIFIgL01vZERhdGUKMjEgMCBSIC9LZXl3b3JkcyAyMiAwIFIgL0FBUEw6S2V5d29yZHMgMjMgMCBSID4+CmVuZG9iagp4cmVmCjAgMjQKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDA2Mzc0IDAwMDAwIG4gCjAwMDAwMDAzMzkgMDAwMDAgbiAKMDAwMDAwMzMyMiAwMDAwMCBuIAowMDAwMDAwMDIyIDAwMDAwIG4gCjAwMDAwMDAzMjAgMDAwMDAgbiAKMDAwMDAwMDQ1MyAwMDAwMCBuIAowMDAwMDAzMjg2IDAwMDAwIG4gCjAwMDAwMDAwMDAgMDAwMDAgbiAKMDAwMDAwMzQ2NSAwMDAwMCBuIAowMDAwMDAwNTUwIDAwMDAwIG4gCjAwMDAwMDMyNjUgMDAwMDAgbiAKMDAwMDAwMzQxNSAwMDAwMCBuIAowMDAwMDA0MDg0IDAwMDAwIG4gCjAwMDAwMDM2NzkgMDAwMDAgbiAKMDAwMDAwNDA2NCAwMDAwMCBuIAowMDAwMDA0MzIwIDAwMDAwIG4gCjAwMDAwMDYxNTEgMDAwMDAgbiAKMDAwMDAwNjE3MiAwMDAwMCBuIAowMDAwMDA2MjE3IDAwMDAwIG4gCjAwMDAwMDYyNzAgMDAwMDAgbiAKMDAwMDAwNjI5MyAwMDAwMCBuIAowMDAwMDA2MzM1IDAwMDAwIG4gCjAwMDAwMDYzNTQgMDAwMDAgbiAKdHJhaWxlcgo8PCAvU2l6ZSAyNCAvUm9vdCAxMiAwIFIgL0luZm8gMSAwIFIgL0lEIFsgPGNlNmU4ZjM2NTU4Y2E3YTg5OGQxZmM0MGRjYmZkZDA4Pgo8Y2U2ZThmMzY1NThjYTdhODk4ZDFmYzQwZGNiZmRkMDg+IF0gPj4Kc3RhcnR4cmVmCjY1MTgKJSVFT0YK",
      "name": "attachment2.pdf"
    }
    ]
  }
}

```    
    
