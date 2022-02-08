<?php

namespace Walkerdistance\LaravelExpress;

use GuzzleHttp\Client;
use Walkerdistance\LaravelExpress\Exceptions\DataOrderException;
use Walkerdistance\LaravelExpress\Exceptions\DataTypeException;
use Walkerdistance\LaravelExpress\Exceptions\HttpException;
use Walkerdistance\LaravelExpress\Exceptions\ExpressResponseException;

class ExpressInquiry
{
    protected $host = 'https://poll.kuaidi100.com/';

    protected $customer = '';

    protected $app_key = '';

    private $defaultClient = null;
    /**
     * 返回数据排序：降序
     */
    private const ORDER_DESC = 'desc';

    /**
     * 返回数据排序：升序
     */
    private const ORDER_ASC = 'asc';

    /**
     * 开启行政区域解析 0:默认(关闭)
     */
    private const RESULT_V2_DEFAULT = 0;
    /**
     * 返回数据类型为 json
     */
    private const DATA_TYPE_JSON = 0;
    /**
     * 返回数据类型为 xml
     */
    private const DATA_TYPE_XML = 1;
    /**
     * 返回数据类型为 html
     */
    private const DATA_TYPE_HTML = 2;
    /**
     * 返回数据类型为 text
     */
    private const DATA_TYPE_TEXT = 3;

    /**
     * @return string
     */
    public function getCustomer(): string
    {
        return $this->customer;
    }

    /**
     * @param string $customer
     */
    public function setCustomer(string $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return string
     */
    public function getAppKey(): string
    {
        return $this->app_key;
    }

    /**
     * @param string $app_key
     */
    public function setAppKey(string $app_key): void
    {
        $this->app_key = $app_key;
    }

    /**
     * @param string $express_number 快递单号
     * @param int $data_type 返回数据类型: 0:json 1:XML 2:HTML 3:TEXT
     * @param string $order 查询结果排序 desc 降序 asc 升序
     * @param int $result_v2 开启行政区域解析 0:默认(关闭) 1：开通行政区域解析功能以及物流轨迹增加物流状态名称 6: 开通行政解析功能以及物流轨迹增加物流高级状态名称、状态值并且返回出发、目的及当前城市信息
     * @param string $phone 手机号
     * @param string $com 快递公司的编码
     * @param string $from 出发地城市
     * @param string $destination 目的地
     * @return mixed|string
     * @throws DataOrderException
     * @throws DataTypeException
     * @throws ExpressResponseException
     * @throws HttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPollQuery(string $express_number, $data_type = self::DATA_TYPE_JSON, $order = self::ORDER_DESC, $result_v2 = self::RESULT_V2_DEFAULT, string $phone = '', string $com = '', string $from = '', string $destination = '')
    {
        if (!in_array($data_type, [self::DATA_TYPE_JSON, self::DATA_TYPE_XML, self::DATA_TYPE_HTML, self::DATA_TYPE_TEXT], true)) {
            throw new DataTypeException('返回数据格式类型错误', 400);
        }
        if (!in_array($order, [self::ORDER_ASC, self::ORDER_DESC], true)) {
            throw new DataOrderException('参数排序错误，非 asc or desc', 400);
        }

        $param = json_encode([
            'com' => $com,
            'num' => $express_number,
            'phone' => $phone,
            'from' => $from,
            'to' => $destination,
            'resultv2' => $result_v2,
            'show' => $data_type,
            'order' => $order,
        ]);
        $data = [
            'customer' => $this->getCustomer(),
            'param' => $param,
            'sign' => strtoupper(md5($param . $this->getAppKey() . $this->getCustomer())),
        ];
        $post_data = "";
        foreach ($data as $k => $v) {
            $post_data .= "$k=" . urlencode($v) . "&";
        }
        $post_data = rtrim($post_data, '&');

        try {
            $response = $this->getClient()->post('poll/query.do?' . $post_data, [
                'header' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode());
        }
        if ($response->getStatusCode() != 200) {
            throw new ExpressResponseException('请求错误', $response->getStatusCode());
        }
        $data = $response->getBody()->getContents();
        if ($data_type === self::DATA_TYPE_JSON) {
            $data = json_decode($data, true);
        }
        return $data;
    }

    /**
     * @param string $express_number 快递单号
     * @param int $data_type 返回数据类型: 0:json 1:XML 2:HTML 3:TEXT
     * @param string $order 查询结果排序 desc 降序 asc 升序
     * @param int $result_v2 开启行政区域解析 0:默认(关闭) 1：开通行政区域解析功能以及物流轨迹增加物流状态名称 6: 开通行政解析功能以及物流轨迹增加物流高级状态名称、状态值并且返回出发、目的及当前城市信息
     * @param string $phone 手机号
     * @param string $com 快递公司的编码
     * @param string $from 出发地城市
     * @param string $destination 目的地
     * @return mixed|string
     * @throws DataOrderException
     * @throws DataTypeException
     * @throws ExpressResponseException
     * @throws HttpException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPollMapTrack(string $express_number, $data_type = self::DATA_TYPE_JSON, $order = self::ORDER_DESC, $result_v2 = self::RESULT_V2_DEFAULT, string $phone = '', string $com = '', string $from = '', string $destination = '')
    {
        if (!in_array($data_type, [self::DATA_TYPE_JSON, self::DATA_TYPE_XML, self::DATA_TYPE_HTML, self::DATA_TYPE_TEXT], true)) {
            throw new DataTypeException('返回数据格式类型错误', 400);
        }
        if (!in_array($order, [self::ORDER_ASC, self::ORDER_DESC], true)) {
            throw new DataOrderException('参数排序错误，非 asc or desc', 400);
        }

        $param = json_encode([
            'com' => $com,
            'num' => $express_number,
            'phone' => $phone,
            'from' => $from,
            'to' => $destination,
            'resultv2' => $result_v2,
            'show' => $data_type,
            'order' => $order,
        ]);
        $data = [
            'customer' => $this->getCustomer(),
            'param' => $param,
            'sign' => strtoupper(md5($param . $this->getAppKey() . $this->getCustomer())),
        ];
        $post_data = "";
        foreach ($data as $k => $v) {
            $post_data .= "$k=" . urlencode($v) . "&";
        }
        $post_data = rtrim($post_data, '&');
        try {
            $response = $this->getClient()->post('poll/maptrack.do?' . $post_data, [
                'header' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ]
            ]);
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode());
        }
        if ($response->getStatusCode() != 200) {
            throw new ExpressResponseException('请求错误', $response->getStatusCode());
        }
        $data = $response->getBody()->getContents();
        if ($data_type === self::DATA_TYPE_JSON) {
            $data = json_decode($data, true);
        }
        return $data;
    }

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->setCustomer($config['customer'] ?? '');
        $this->setAppKey($config['api_key'] ?? '');
    }

    /**
     * @return Client|null
     */
    private function getClient()
    {
        if (!$this->defaultClient) {
            return new Client([
                'base_uri' => $this->host,
                'timeout' => 30,
            ]);
        }
        return $this->defaultClient;
    }
}