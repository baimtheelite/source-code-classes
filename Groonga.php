<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Ixudra\Curl\Facades\Curl;

class Groonga {
    protected static $groonga;
    protected static $data = [];

    public static function select($table)
    {
        self::$groonga = Curl::to(config('app.url_groonga') . "/select?table={$table}");

        return new self;
    }

    public static function load($table)
    {
        self::$groonga = Curl::to(config('app.url_groonga') . "/load?table={$table}");

        return new self;
    }

    public static function limit($limit = 10)
    {
        self::$data = array_merge(self::$data, ['limit' => $limit]);

        return new self;
    }

    public static function offset($offset)
    {
        self::$data = array_merge(self::$data, ['offset' => $offset]);

        return new self;
    }

    public static function sortKeys($sort)
    {
        self::$data = array_merge(self::$data, ['sort_keys' => $sort]);

        return new self;
    }

    public static function matchColumns(array $columns)
    {
        self::$data = array_merge(self::$data, ['match_columns' => implode(',', $columns)]);

        return new self;
    }

    public static function outputColumns(array $columns)
    {
        self::$data = array_merge(self::$data, ['output_columns' => implode(',', $columns)]);

        return new self;
    }

    public static function query($query)
    {
        self::$data = array_merge(self::$data, ['query' => $query]);

        return new self;
    }

    public function get()
    {

        $groonga = self::$groonga->withData(self::$data)->asJson(true)->get();

        // dd(self::$data['outp']);

        $outputColumns = explode(',', self::$data['output_columns']);
        // dd($outputColumns);

        $collect = collect($groonga[1][0])->filter(function($value, $key) {
            return $key >= 2;
        })->values()->map(function($item, $key) use ($outputColumns) {
             $output = [];
            foreach ($outputColumns as $outputKey => $outputValue) {
                $output = array_merge($output, [$outputValue => $item[$outputKey]]);
            }

            return $output;
        });

        return $collect->all();
    }

    public function first()
    {

        $groonga = self::$groonga->withData(self::$data)->asJson(true)->get();

        $outputColumns = explode(',', self::$data['output_columns']);

        $collect = collect($groonga[1][0])->filter(function($value, $key) {
            return $key >= 2;
        })->values()->map(function($item, $key) use ($outputColumns) {
             $output = [];
            foreach ($outputColumns as $outputKey => $outputValue) {
                $output = array_merge($output, [$outputValue => $item[$outputKey]]);
            }

            return $output;
        });


        return $collect->first();
    }

    public static function loadRecords(array $data)
    {
        self::$data = array_merge(self::$data, $data);

        return new self;
    }

    public static function post()
    {
        return self::$groonga->withData(self::$data)->asJson(true)->post();
    }

    public static function viewTable($params)
    {
        return Curl::to(config('app.url_groonga') . '/select')->withData($params)->asJson()->get();
    }
}
