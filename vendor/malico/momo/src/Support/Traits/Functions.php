<?php

namespace Malico\Momo\Support\Traits;

use GuzzleHttp\Client;
use Malico\Momo\Exceptions\ConnectionFailure;

trait Functions
{
    /**
     * Create new MOMO Request.
     *
     * @param string, number $tel   Client Telephone Number
     * @param int            $price
     */
    public function __construct($tel, $price = null)
    {
        $this->tel = $tel;
        $this->amount = $price ?? config('momo.default_price');
    }

    /**
     * Make Transaction and Record.
     *
     * @return Transaction
     */
    public function pay()
    {
        $client = new Client(['http_errors' => false]);

        $query = [
            'idbouton'   => $this->idbouton,
            'typebouton' => $this->typebouton,
            '_amount'    => $this->amount,
            '_tel'       => $this->tel,
            '_clP'       => $this->cpl,
            '_email'     => $this->email ?? config('momo.email'),
        ];

        $response = $client->request(
            'GET',
            $this->url,
            [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false,
                ],
                'query' => $query,
            ]
        );

        if ($response->getStatusCode() != 200) {
            ConnectionFailure::failedConnection("Can't connect to MTN Servers");

            return;
        } else {
            $this->recordTransation(json_decode($response->getBody(), true));

            return $this;
        }
    }

    /**
     * Save Transaction to DB.
     *
     * @return Transaction
     */
    protected function recordTransation(array $trans)
    {
        $this->transaction['amount'] = $trans['Amount'];
        $this->transaction['tel'] = $trans['SenderNumber'];
        $this->transaction['status'] = (int) $trans['StatusCode'] == 1 ? true : false;
        $this->transaction['comment'] = $trans['OpComment'];
        $this->transaction['reference'] = $trans['ProcessingNumber'];
        $this->transaction['receiver_tel'] = $trans['ReceiverNumber'];
        $this->transaction['operation_type'] = $trans['OperationType'];
        $this->transaction['transaction_id'] = $trans['TransactionID'];

        if ($trans['StatusCode'] == '01') {
            $this->transaction['desc'] = $trans['StatusDesc'];
        } elseif ($trans['StatusCode'] == '529') {
            $this->transaction['desc'] = 'Insufficient Balanced';
        } elseif ($trans['StatusCode'] == '100') {
            $this->transaction['desc'] = 'Transaction Denied';
        } else {
            $this->transaction['desc'] = 'Transaction was not confirmed';
        }
    }

    /**
     * Get Transacton Value.
     *
     * @param string $name
     *
     * @return string|int|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->transaction)) {
            return $this->transaction[$name];
        }
    }

    /**
     * [jsonSerialize Momo Transaction].
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->transaction;
    }
}
