<?php

namespace App\Message;

class ServiceMethodCallMessage
{

    private string $serviceName;
    private string $methodName;
    private array $params;

    public function __construct(string $serviceName, string $methodName, array $params = []) {
        $this->serviceName = $serviceName;
        $this->methodName = $methodName;
        $this->params = $params;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getParams(): array
    {
        return $this->params;
    }

}
