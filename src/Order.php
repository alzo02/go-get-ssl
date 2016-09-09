<?php

namespace GoGetSSL;

/**
 * Class Order
 *
 * @package GoGetSSL
 * @author alzo02 <alzo02@icloud.com>
 */
class Order
{
    const STATUS_ACTIVE             = 'active';
    const STATUS_PENDING            = 'pending';
    const STATUS_CANCELED           = 'cancelled';
    const STATUS_REJECTED           = 'rejected';
    const STATUS_PAYMENT_NEEDED     = 'payment needed';
    const STATUS_PROCESSING         = 'processing';
    const STATUS_INCOMPLETE         = 'incomplete';

    protected $api;

    protected $tools;

    /**
     * Product constructor.
     * @param Api $api
     */
    function __construct(Api $api)
    {
        $this->api = $api;
        $this->tools = new Tools($api);
    }

    /**
     * @param array $data
     *      Following request parameters
     *          - product_id            - required - product ID, can be taken from getAllProducts methods
     *          - period                - required - period in months
     *          - csr                   - required - CSR code for SSL certificate
     *          - server_count          - required - amount of servers, for Unlimited pass “-1”
     *          - approver_email        - required - DCV approver email for the primary domain,
     *                                      can be taken from the list provided by getDomainEmails() methods.
     *                                      This parameter must be used only if dcv_method prameter value is 'email'.
     *          - webserver_type        - required - webserver type, can be taken from getWebservers() method
     *          - dns_names             – required for SAN/UCC/Multi-Domain SSL, for the rest of products this parameter
     *                                      must not be provided. A comma separated list of additional domain names.
     *                                      The list must not contain the primary domain.
     *          - admin_firstname       - required
     *          - admin_lastname        - required
     *          - admin_organization    - required for OV SSL certificates
     *          - admin_addressline1
     *          - admin_phone           - required
     *          - admin_title           - required
     *          - admin_email           - required
     *          - admin_city            - required for OV SSL certificates
     *          - admin_country         - required for OV SSL certificates
     *          - admin_fax             - required for OV SSL certificates
     *          - admin_postalcode
     *          - admin_region
     *          - dcv_method            – required. Value of this specifies DCV method to be used.
     *                                      Possible values: 'email', 'http', 'https', 'dns'.
     *          - tech_firstname        - required
     *          - tech_lastname         - required
     *          - tech_organization     - required for OV SSL certificates
     *          - tech_addressline1     - required
     *          - tech_phone            - required
     *          - tech_title            - required
     *          - tech_email            - required
     *          - tech_city             - required for OV SSL certificates
     *          - tech_country          - required for OV SSL certificates
     *          - tech_fax
     *          - tech_postalcode
     *          - tech_region
     *          - org_name              - required for OV SSL certificates
     *          - org_division          - required for OV SSL certificates
     *          - org_duns
     *          - org_addressline1      - required for OV SSL certificates
     *          - org_city              - required for OV SSL certificates
     *          - org_country           - required for OV SSL certificates
     *          - org_fax
     *          - org_phone             - required for OV SSL certificates
     *          - org_postalcode        - required for OV SSL certificates
     *          - org_region            - required for OV SSL certificates
     *
     * @note: Quantity of items in the approver_emails list must be always equal to quantity of
     *        items in the dns_names list.
     *
     * @return array
     *      If no errors in request following parameters will be returned:
     *          - order_id      - unique order ID
     *          - invoice_id    - unique invoice ID
     *          - order_amount  - order amount
     *          - currency      - order currency
     *          - tax           - order tax if applicable
     *          - tax_rate      - order tax rate if applicable
     *          - success       - success code (true)
     */
    public function addSSLOrder(array $data)
    {
        return $this->api->post('/orders/add_ssl_order/', $data);
    }

    /**
     * AddSSLRenewOrder allows a Partner to do everything a requestor would typically do using our web forms
     * for placing an renew order via one API operation call.
     *
     * Request parameters
     *      The same as for the addSSLOrder method.
     * Response
     *      The same as for the addSSLOrder method
     *
     * @param array $data
     * @return mixed
     */
    public function addSSLRenewOrder(array $data)
    {
        return $this->api->post('/orders/add_ssl_order/', $data);
    }

    /**
     * Order additional SAN/s method. Use that method to add more SAN names to your Multi-Domain SSL certificates,
     * once SAN is added to the order, you will need to reissue SSL.
     *
     * Request parameters
     *      order_id    - ORDER ID to which we will add new SAN
     *      count       - Quantity of SAN names to add
     * Response
     *  If no errors in request following parameters will be returned:
     *      - order_id - unique order ID
     *      - invoice_id
     *
     * @param array $data
     * @return mixed
     */
    public function addSSLSANOrder(array $data)
    {
        return $this->api->post("/orders/add_ssl_san_order/", $data);
    }

    /**
     * The getOrderStatus returns detailed information for the order matching “order_id” parameter.
     * “order_id” is returned during addSSLOrder command.
     *
     * @param $order_id
     * @return \stdClass | null
     * If no errors in request details parameters will be returned.
     */
    public function getOrderStatus($order_id)
    {
        return $this->api->get("/orders/status/{$order_id}");
    }

    /**
     * The getOrderInvoice returns detailed information for the invoice matching “invoice_id” parameter.
     * “invoice_id” is returned during addSSLOrder command.
     *
     * @param $order_id
     * @return mixed
     */
    public function getOrderInvoice($order_id)
    {
        return $this->api->get("/orders/invoice/{$order_id}");
    }

    /**
     * Returns list of all unpaid orders.
     */
    public function getUnpaidOrders()
    {
        return $this->api->get("/orders/list/unpaid/");
    }

    /**
     * The reIssueSSLOrder method process with SSL certificate reissue procedure if you have lost your private key,
     * or you want to add more SAN names for Multi Domain or UCC orders.
     *
     * @param int $order_id
     *
     * @param array $data
     *
     * Request parameters
     *  - order_id          - your order ID returned in addSSLOrder method
     *  - csr               - CSR code for SSL certificate
     *  - approver_email    - approver email, can be taken from getDomainEmails* methods
     *  - approver_emails   - A comma separated list of domain control validation e-mail addresses. One
     *                           and only one e-mail must be provided for each additional domain.
     *                           DCV e-mail address for the primary domain must not be included to the list.
     *  - webserver_type    - webserver type, can be taken from getWebservers method.
     *  - dns_names         – Required for SAN/UCC/Multi-Domain SSL. A comma separated list of additional domain names.
     *                         The list must not contain the primary domain.
     *  - dcv_method        – Domain Control Validation method (email, http, dns).
     *
     * @return \stdClass
     *
     *   If no errors in request following parameters will be returned:
     *   - order_id         - unique order ID
     *   - order_status     - order status (reissue)
     *   - validation       – Contains validation invormation in case of http and dnc DCV methods
     *   - success          - success code (true)
     */
    public function reIssueSSLOrder($order_id, array $data)
    {
        return $this->api->post("/orders/ssl/reissue/{$order_id}", $data);
    }

    /**
     * The resendValidationEmail method re-sends validation e-mail for the order matching “order_id” parameter.
     *
     * @param $order_id
     *
     * @return \stdClass
     *
     *  If no errors in request following parameters will be returned:
     *   - message       - error message or code
     *   - success       - result code
     *
     */
    public function resendValidationEmail($order_id)
    {
        return $this->api->post("/orders/ssl/resend_validation_email/{$order_id}");
    }

    /**
     * Use that option to request cancellation/refund of any order.
     * Please note, we respond during 24-48 hours to all requests.
     *
     * @param array $data
     *
     * Request parameters
     *  - order_id      - ID of the order to be cancelled
     *  - reason        – Cancellation reason
     *
     * @return \stdClass
     *
     *   If no errors in request following parameters will be returned:
     *   - order_id         - unique order ID
     */
    public function cancelSSLOrder(array $data)
    {
        return $this->api->post("/orders/cancel_ssl_order/", $data);
    }
}