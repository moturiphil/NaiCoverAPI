# API Examples

Practical examples of using the NaiCover API for common insurance platform scenarios.

## Table of Contents

- [Authentication Examples](#authentication-examples)
- [Customer Onboarding](#customer-onboarding)
- [Policy Management](#policy-management)
- [Agent Operations](#agent-operations)
- [Notification Examples](#notification-examples)
- [Error Handling](#error-handling)
- [Integration Patterns](#integration-patterns)

## Authentication Examples

### User Registration and Login Flow

```bash
# 1. Register new user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "SecurePass123",
    "password_confirmation": "SecurePass123"
  }'
```

Response:
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "created_at": "2024-01-01T12:00:00.000000Z"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
    "token_type": "Bearer"
}
```

```bash
# 2. Login existing user
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "SecurePass123"
  }'
```

```bash
# 3. Access protected endpoint
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
```

### JavaScript Authentication Example

```javascript
class NaiCoverAPIClient {
    constructor(baseURL = 'http://localhost:8000/api') {
        this.baseURL = baseURL;
        this.token = localStorage.getItem('naicoverapi_token');
    }

    async register(userData) {
        const response = await fetch(`${this.baseURL}/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(userData)
        });
        
        const data = await response.json();
        
        if (data.access_token) {
            this.token = data.access_token;
            localStorage.setItem('naicoverapi_token', this.token);
        }
        
        return data;
    }

    async login(email, password) {
        const response = await fetch(`${this.baseURL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (data.access_token) {
            this.token = data.access_token;
            localStorage.setItem('naicoverapi_token', this.token);
        }
        
        return data;
    }

    async makeAuthenticatedRequest(endpoint, options = {}) {
        const response = await fetch(`${this.baseURL}${endpoint}`, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${this.token}`,
                ...options.headers
            }
        });
        
        return response.json();
    }
}

// Usage
const api = new NaiCoverAPIClient();

// Register user
const userData = await api.register({
    name: "John Doe",
    email: "john@example.com",
    password: "SecurePass123",
    password_confirmation: "SecurePass123"
});

// Get user profile
const profile = await api.makeAuthenticatedRequest('/user');
```

### PHP Authentication Example

```php
<?php

class NaiCoverAPIClient {
    private $baseURL;
    private $token;

    public function __construct($baseURL = 'http://localhost:8000/api') {
        $this->baseURL = $baseURL;
    }

    public function register($userData) {
        $response = $this->makeRequest('/register', $userData);
        
        if (isset($response['access_token'])) {
            $this->token = $response['access_token'];
        }
        
        return $response;
    }

    public function login($email, $password) {
        $response = $this->makeRequest('/login', [
            'email' => $email,
            'password' => $password
        ]);
        
        if (isset($response['access_token'])) {
            $this->token = $response['access_token'];
        }
        
        return $response;
    }

    public function makeAuthenticatedRequest($endpoint, $data = null, $method = 'GET') {
        $headers = [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ];
        
        return $this->makeRequest($endpoint, $data, $method, $headers);
    }

    private function makeRequest($endpoint, $data = null, $method = 'POST', $headers = []) {
        $ch = curl_init($this->baseURL . $endpoint);
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $defaultHeaders = ['Content-Type: application/json'];
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($defaultHeaders, $headers));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
}

// Usage
$api = new NaiCoverAPIClient();

// Register user
$userData = $api->register([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => 'SecurePass123',
    'password_confirmation' => 'SecurePass123'
]);

// Get user profile
$profile = $api->makeAuthenticatedRequest('/user', null, 'GET');
```

## Customer Onboarding

### Complete Customer Onboarding Flow

```bash
# Step 1: Create customer profile
TOKEN="your_access_token_here"

curl -X POST http://localhost:8000/api/customers \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "date_of_birth": "1990-01-15",
    "address": "123 Main St",
    "city": "New York",
    "state": "NY",
    "zip_code": "10001"
  }'
```

Response:
```json
{
    "id": 1,
    "user_id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "status": "active",
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

```bash
# Step 2: Send welcome notification
curl -X POST http://localhost:8000/api/notifications/welcome \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1
  }'
```

### JavaScript Customer Onboarding

```javascript
async function onboardCustomer(customerData) {
    const api = new NaiCoverAPIClient();
    
    try {
        // Create customer
        const customer = await api.makeAuthenticatedRequest('/customers', {
            method: 'POST',
            body: JSON.stringify(customerData)
        });
        
        console.log('Customer created:', customer);
        
        // Send welcome notification
        const notification = await api.makeAuthenticatedRequest('/notifications/welcome', {
            method: 'POST',
            body: JSON.stringify({ user_id: customer.user_id })
        });
        
        console.log('Welcome notification sent:', notification);
        
        return { customer, notification };
    } catch (error) {
        console.error('Onboarding failed:', error);
        throw error;
    }
}

// Usage
const customerData = {
    user_id: 1,
    first_name: "John",
    last_name: "Doe",
    email: "john@example.com",
    phone: "+1234567890",
    date_of_birth: "1990-01-15",
    address: "123 Main St",
    city: "New York",
    state: "NY",
    zip_code: "10001"
};

onboardCustomer(customerData);
```

## Policy Management

### Creating a Complete Policy

```bash
# Step 1: Create policy
curl -X POST http://localhost:8000/api/policies \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "policy_number": "POL2024001",
    "customer_id": 1,
    "agent_id": 1,
    "provider_id": 1,
    "policy_type_id": 1,
    "premium_amount": 1200.00,
    "start_date": "2024-01-01",
    "end_date": "2024-12-31",
    "coverage_details": {
      "coverage_amount": 100000,
      "deductible": 1000,
      "coverage_type": "comprehensive"
    }
  }'
```

Response:
```json
{
    "id": 1,
    "policy_number": "POL2024001",
    "customer_id": 1,
    "agent_id": 1,
    "provider_id": 1,
    "premium_amount": "1200.00",
    "status": "active",
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

```bash
# Step 2: Add policy attributes (vehicle details for auto insurance)
curl -X POST http://localhost:8000/api/policy_attribute_values \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "policy_id": 1,
    "policy_attribute_id": 1,
    "value": "Toyota Camry"
  }'

curl -X POST http://localhost:8000/api/policy_attribute_values \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "policy_id": 1,
    "policy_attribute_id": 2,
    "value": "2022"
  }'
```

```bash
# Step 3: Send policy created notification
curl -X POST http://localhost:8000/api/notifications/policy-created \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "policy_id": 1
  }'
```

### Policy Search and Filtering

```bash
# Search policies by customer
curl -X GET "http://localhost:8000/api/policies?customer_id=1" \
  -H "Authorization: Bearer $TOKEN"

# Search policies by status and date range
curl -X GET "http://localhost:8000/api/policies?status=active&start_date=2024-01-01&end_date=2024-12-31" \
  -H "Authorization: Bearer $TOKEN"

# Search policies with pagination
curl -X GET "http://localhost:8000/api/policies?page=2&per_page=10" \
  -H "Authorization: Bearer $TOKEN"
```

### Bulk Policy Operations

```javascript
async function createBulkPolicies(policies) {
    const api = new NaiCoverAPIClient();
    const results = [];
    
    for (const policyData of policies) {
        try {
            const policy = await api.makeAuthenticatedRequest('/policies', {
                method: 'POST',
                body: JSON.stringify(policyData)
            });
            
            // Send notification for each policy
            await api.makeAuthenticatedRequest('/notifications/policy-created', {
                method: 'POST',
                body: JSON.stringify({ policy_id: policy.id })
            });
            
            results.push({ success: true, policy });
        } catch (error) {
            results.push({ 
                success: false, 
                error: error.message, 
                data: policyData 
            });
        }
    }
    
    return results;
}

// Usage
const bulkPolicies = [
    {
        policy_number: "POL2024001",
        customer_id: 1,
        agent_id: 1,
        provider_id: 1,
        policy_type_id: 1,
        premium_amount: 1200.00,
        start_date: "2024-01-01",
        end_date: "2024-12-31"
    },
    {
        policy_number: "POL2024002",
        customer_id: 2,
        agent_id: 1,
        provider_id: 1,
        policy_type_id: 2,
        premium_amount: 800.00,
        start_date: "2024-01-01",
        end_date: "2024-12-31"
    }
];

createBulkPolicies(bulkPolicies).then(results => {
    console.log('Bulk creation results:', results);
});
```

## Agent Operations

### Agent Dashboard Data

```bash
# Get agent profile with statistics
curl -X GET "http://localhost:8000/api/agents/1" \
  -H "Authorization: Bearer $TOKEN"
```

Response:
```json
{
    "id": 1,
    "agent_code": "AGT001",
    "first_name": "Sarah",
    "last_name": "Wilson",
    "email": "sarah@agency.com",
    "phone": "+1234567890",
    "license_number": "LIC123456",
    "commission_rate": "5.00",
    "status": "active",
    "policies_count": 25,
    "total_commission": "6000.00",
    "monthly_sales": [
        { "month": "2024-01", "count": 5, "premium": 6000.00 },
        { "month": "2024-02", "count": 8, "premium": 9600.00 }
    ],
    "created_at": "2024-01-01T12:00:00.000000Z"
}
```

### Agent Performance Analytics

```javascript
async function getAgentPerformance(agentId, period = '3months') {
    const api = new NaiCoverAPIClient();
    
    try {
        // Get agent details
        const agent = await api.makeAuthenticatedRequest(`/agents/${agentId}`);
        
        // Get agent's policies
        const policies = await api.makeAuthenticatedRequest(`/policies?agent_id=${agentId}`);
        
        // Calculate performance metrics
        const metrics = calculatePerformanceMetrics(policies.data);
        
        return {
            agent,
            policies: policies.data,
            metrics
        };
    } catch (error) {
        console.error('Failed to get agent performance:', error);
        throw error;
    }
}

function calculatePerformanceMetrics(policies) {
    const totalPolicies = policies.length;
    const totalPremium = policies.reduce((sum, p) => sum + parseFloat(p.premium_amount), 0);
    const averagePremium = totalPolicies > 0 ? totalPremium / totalPolicies : 0;
    
    const statusCounts = policies.reduce((counts, p) => {
        counts[p.status] = (counts[p.status] || 0) + 1;
        return counts;
    }, {});
    
    return {
        total_policies: totalPolicies,
        total_premium: totalPremium,
        average_premium: averagePremium,
        status_breakdown: statusCounts,
        conversion_rate: statusCounts.active / totalPolicies * 100 || 0
    };
}
```

## Notification Examples

### Bulk Notification Campaign

```bash
# Send bulk welcome notifications
curl -X POST http://localhost:8000/api/notifications/bulk \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "user_ids": [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    "notification_type": "welcome",
    "data": {
      "campaign_name": "Q1 Welcome Campaign",
      "custom_message": "Welcome to our enhanced insurance platform!"
    }
  }'
```

Response:
```json
{
    "success": true,
    "message": "Bulk notifications processed",
    "data": {
        "sent": 8,
        "failed": 2,
        "errors": [
            {
                "user_id": 5,
                "error": "User not found"
            },
            {
                "user_id": 9,
                "error": "Invalid email address"
            }
        ]
    }
}
```

### Policy Renewal Reminders

```javascript
async function sendPolicyRenewalReminders() {
    const api = new NaiCoverAPIClient();
    
    // Get policies expiring in 30 days
    const expiringDate = new Date();
    expiringDate.setDate(expiringDate.getDate() + 30);
    const expiringDateStr = expiringDate.toISOString().split('T')[0];
    
    try {
        const policies = await api.makeAuthenticatedRequest(
            `/policies?end_date=${expiringDateStr}&status=active`
        );
        
        const customerIds = policies.data.map(p => p.customer.user_id);
        
        if (customerIds.length > 0) {
            const result = await api.makeAuthenticatedRequest('/notifications/bulk', {
                method: 'POST',
                body: JSON.stringify({
                    user_ids: customerIds,
                    notification_type: "policy_renewal_reminder",
                    data: {
                        renewal_date: expiringDateStr,
                        message: "Your policy will expire soon. Please contact us to renew."
                    }
                })
            });
            
            console.log(`Sent renewal reminders to ${result.data.sent} customers`);
            return result;
        }
    } catch (error) {
        console.error('Failed to send renewal reminders:', error);
        throw error;
    }
}
```

### Custom Notification Templates

```php
<?php
// Custom notification service
class CustomNotificationService {
    private $apiClient;
    
    public function __construct($apiClient) {
        $this->apiClient = $apiClient;
    }
    
    public function sendPolicyUpdateNotification($policyId, $changes) {
        $policy = $this->apiClient->makeAuthenticatedRequest("/policies/{$policyId}", null, 'GET');
        
        if (!$policy) {
            throw new Exception("Policy not found");
        }
        
        $customData = [
            'policy_number' => $policy['policy_number'],
            'changes' => $changes,
            'effective_date' => date('Y-m-d'),
            'contact_info' => 'support@naicoverapi.com'
        ];
        
        return $this->apiClient->makeAuthenticatedRequest('/notifications/custom', [
            'user_id' => $policy['customer']['user_id'],
            'type' => 'policy_update',
            'data' => $customData
        ]);
    }
    
    public function sendClaimStatusUpdate($claimId, $status) {
        // Implementation for claim status notifications
        return $this->apiClient->makeAuthenticatedRequest('/notifications/custom', [
            'type' => 'claim_update',
            'data' => [
                'claim_id' => $claimId,
                'status' => $status,
                'timestamp' => date('c')
            ]
        ]);
    }
}
```

## Error Handling

### Robust Error Handling Pattern

```javascript
class APIError extends Error {
    constructor(response, data) {
        super(data.message || 'API Error');
        this.status = response.status;
        this.data = data;
        this.errors = data.errors || {};
    }
}

async function makeRobustAPICall(endpoint, options = {}) {
    const api = new NaiCoverAPIClient();
    const maxRetries = 3;
    let lastError;
    
    for (let attempt = 1; attempt <= maxRetries; attempt++) {
        try {
            const response = await fetch(`${api.baseURL}${endpoint}`, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${api.token}`,
                    ...options.headers
                }
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new APIError(response, data);
            }
            
            return data;
        } catch (error) {
            lastError = error;
            
            // Don't retry on client errors (4xx)
            if (error.status && error.status >= 400 && error.status < 500) {
                throw error;
            }
            
            // Exponential backoff for retries
            if (attempt < maxRetries) {
                const delay = Math.pow(2, attempt - 1) * 1000; // 1s, 2s, 4s
                await new Promise(resolve => setTimeout(resolve, delay));
                console.log(`Retrying API call (attempt ${attempt + 1}/${maxRetries}) after ${delay}ms`);
            }
        }
    }
    
    throw lastError;
}

// Usage with comprehensive error handling
async function createPolicyWithErrorHandling(policyData) {
    try {
        const policy = await makeRobustAPICall('/policies', {
            method: 'POST',
            body: JSON.stringify(policyData)
        });
        
        console.log('Policy created successfully:', policy);
        return policy;
    } catch (error) {
        if (error instanceof APIError) {
            switch (error.status) {
                case 401:
                    console.error('Authentication failed. Please login again.');
                    // Redirect to login
                    break;
                case 403:
                    console.error('Insufficient permissions to create policy.');
                    break;
                case 422:
                    console.error('Validation errors:', error.errors);
                    // Show validation errors to user
                    break;
                case 500:
                    console.error('Server error. Please try again later.');
                    break;
                default:
                    console.error('Unexpected error:', error.message);
            }
        } else {
            console.error('Network or other error:', error.message);
        }
        throw error;
    }
}
```

### Validation Error Handling

```javascript
function handleValidationErrors(errors) {
    const errorMessages = [];
    
    for (const [field, messages] of Object.entries(errors)) {
        for (const message of messages) {
            errorMessages.push({
                field,
                message,
                element: document.querySelector(`[name="${field}"]`)
            });
        }
    }
    
    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.remove());
    document.querySelectorAll('.error').forEach(el => el.classList.remove('error'));
    
    // Display new errors
    errorMessages.forEach(({ field, message, element }) => {
        if (element) {
            element.classList.add('error');
            const errorEl = document.createElement('div');
            errorEl.className = 'error-message';
            errorEl.textContent = message;
            element.parentNode.appendChild(errorEl);
        }
    });
    
    return errorMessages;
}

// Usage in form submission
async function submitPolicyForm(formData) {
    try {
        const policy = await createPolicyWithErrorHandling(formData);
        showSuccessMessage('Policy created successfully!');
        return policy;
    } catch (error) {
        if (error instanceof APIError && error.status === 422) {
            const validationErrors = handleValidationErrors(error.errors);
            showErrorMessage(`Please fix ${validationErrors.length} validation errors.`);
        } else {
            showErrorMessage('Failed to create policy. Please try again.');
        }
    }
}
```

## Integration Patterns

### Webhook Integration

```php
<?php
// Webhook handler for external integrations
class WebhookHandler {
    public function handlePolicyCreated($policyData) {
        // Send to external CRM system
        $this->sendToCRM($policyData);
        
        // Update external reporting system
        $this->updateReporting($policyData);
        
        // Trigger automated workflows
        $this->triggerWorkflows($policyData);
    }
    
    private function sendToCRM($policyData) {
        $crmClient = new CRMClient();
        $crmClient->createOpportunity([
            'policy_id' => $policyData['id'],
            'customer_name' => $policyData['customer']['name'],
            'premium_amount' => $policyData['premium_amount'],
            'status' => 'closed_won'
        ]);
    }
    
    private function updateReporting($policyData) {
        $reportingAPI = new ReportingAPI();
        $reportingAPI->recordSale([
            'date' => date('Y-m-d'),
            'agent_id' => $policyData['agent_id'],
            'premium' => $policyData['premium_amount'],
            'policy_type' => $policyData['policy_type']['name']
        ]);
    }
}
```

### Scheduled Data Synchronization

```javascript
// Scheduled task for data synchronization
class DataSyncService {
    constructor(apiClient) {
        this.api = apiClient;
        this.lastSyncTimestamp = localStorage.getItem('last_sync') || new Date().toISOString();
    }
    
    async syncPolicies() {
        try {
            console.log('Starting policy synchronization...');
            
            // Get policies modified since last sync
            const policies = await this.api.makeAuthenticatedRequest(
                `/policies?modified_since=${this.lastSyncTimestamp}`
            );
            
            // Process each policy
            for (const policy of policies.data) {
                await this.processPolicyUpdate(policy);
            }
            
            // Update last sync timestamp
            this.lastSyncTimestamp = new Date().toISOString();
            localStorage.setItem('last_sync', this.lastSyncTimestamp);
            
            console.log(`Synchronized ${policies.data.length} policies`);
        } catch (error) {
            console.error('Synchronization failed:', error);
            throw error;
        }
    }
    
    async processPolicyUpdate(policy) {
        // Update local database
        await this.updateLocalPolicy(policy);
        
        // Send to external systems if needed
        if (policy.status === 'active') {
            await this.notifyExternalSystems(policy);
        }
    }
    
    // Run synchronization every 15 minutes
    startScheduledSync() {
        setInterval(async () => {
            try {
                await this.syncPolicies();
            } catch (error) {
                console.error('Scheduled sync failed:', error);
            }
        }, 15 * 60 * 1000); // 15 minutes
    }
}
```

### Real-time Updates with WebSockets

```javascript
// WebSocket client for real-time updates
class RealTimeUpdates {
    constructor(apiClient) {
        this.api = apiClient;
        this.ws = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
    }
    
    connect() {
        const wsUrl = `ws://localhost:8000/ws?token=${this.api.token}`;
        this.ws = new WebSocket(wsUrl);
        
        this.ws.onopen = () => {
            console.log('WebSocket connected');
            this.reconnectAttempts = 0;
            
            // Subscribe to relevant events
            this.subscribe(['policy_created', 'policy_updated', 'payment_received']);
        };
        
        this.ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleMessage(data);
        };
        
        this.ws.onclose = () => {
            console.log('WebSocket disconnected');
            this.attemptReconnect();
        };
        
        this.ws.onerror = (error) => {
            console.error('WebSocket error:', error);
        };
    }
    
    subscribe(events) {
        if (this.ws.readyState === WebSocket.OPEN) {
            this.ws.send(JSON.stringify({
                type: 'subscribe',
                events: events
            }));
        }
    }
    
    handleMessage(data) {
        switch (data.type) {
            case 'policy_created':
                this.onPolicyCreated(data.payload);
                break;
            case 'policy_updated':
                this.onPolicyUpdated(data.payload);
                break;
            case 'payment_received':
                this.onPaymentReceived(data.payload);
                break;
            default:
                console.log('Unknown message type:', data.type);
        }
    }
    
    onPolicyCreated(policy) {
        console.log('New policy created:', policy);
        // Update UI
        this.updatePolicyList(policy);
        this.showNotification(`New policy created: ${policy.policy_number}`);
    }
    
    onPolicyUpdated(policy) {
        console.log('Policy updated:', policy);
        // Update existing policy in UI
        this.updatePolicyInList(policy);
    }
    
    attemptReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            const delay = Math.pow(2, this.reconnectAttempts) * 1000;
            
            setTimeout(() => {
                console.log(`Attempting to reconnect (${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
                this.connect();
            }, delay);
        } else {
            console.error('Max reconnection attempts reached');
        }
    }
}
```

These examples provide practical, production-ready code for integrating with the NaiCover API in various scenarios and programming languages.