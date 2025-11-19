<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\User;

class ListUsersTool extends Tool
{
    protected User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        List all users with pagination support and optional search by name or email.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'per_page' => 'integer|min:1|max:100',
            'page' => 'integer|min:1',
            'search' => 'string|max:255',
        ]);

        $perPage = $validated['per_page'] ?? 15;
        $page = $validated['page'] ?? 1;
        $search = $validated['search'] ?? null;

        $query = $this->userModel->query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')
                       ->paginate($perPage, ['*'], 'page', $page);

        $output = "Users List\n";
        $output .= "==========\n\n";
        $output .= "Total: {$users->total()} user(s)\n";
        $output .= "Page: {$users->currentPage()} of {$users->lastPage()}\n";
        $output .= "Showing: {$users->count()} user(s)\n\n";

        if ($users->isEmpty()) {
            $output .= "No users found.\n";
        } else {
            foreach ($users as $index => $user) {
                $number = ($users->currentPage() - 1) * $perPage + $index + 1;
                $output .= "#{$number} - ID: {$user->id}\n";
                $output .= "   Name: {$user->name}\n";
                $output .= "   Email: {$user->email}\n";
                $output .= "   Created: {$user->created_at->format('Y-m-d H:i:s')}\n";
                $output .= "\n";
            }
        }

        return Response::text($output);
    }

    /**
     * Get the tool's input schema.
     * Specify what arguments they accept from AI clients.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'per_page' => $schema->integer()
                ->description('Number of users per page (default: 15, max: 100)')
                ->default(15),
            
            'page' => $schema->integer()
                ->description('Page number to retrieve (default: 1)')
                ->default(1),
            
            'search' => $schema->string()
                ->description('Search term to filter users by name or email')

        ];
    }
}