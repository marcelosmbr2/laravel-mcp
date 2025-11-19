<?php

use Laravel\Mcp\Facades\Mcp;
use App\Mcp\Servers\UserServer;

Mcp::web('/mcp/users', UserServer::class);
