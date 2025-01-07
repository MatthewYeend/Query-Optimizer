<?php

namespace MattYeend\QueryOptimizer;

use Closure;
use DB;
use Log;

class LogQueries{
    public function handle($request, Closure $next)
    {
        DB::enableQueryLog();
        $response = $next($request);

        if(config('query_optimizer.log_slow_queries')){
            $queries = DB::getQueryLog();
            foreach($queries as $query){
                if($query['time'] > config('query_optimizer.slow_query_threshold')){
                    Log::warning('Slow Query Detected', [
                        'SQL' => $query['query'],
                        'Bindings' => $query['bindings'],
                        'Time' => $query['time'],
                    ]);
                }
            }
        }
        return $response;
    }
}