import { usePage } from "@inertiajs/react";
import { AppLayout } from "@/layouts/app-layout";
// Shadcn UI components
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableFooter,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table"

interface User {
    id: number;
    name: string;
    email: string;
    created_at: string;
    updated_at: string;
}

export default function UsersPage() {

    const { users }: any = usePage().props;

    return (
        <AppLayout>
            <Table>
                <TableCaption>A list of registered users.</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead className="w-[100px]">Name</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Created at</TableHead>
                        <TableHead className="text-right">Updated at</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {users.data.map((user: User) => (
                        <TableRow key={user.id}>
                            <TableCell className="font-medium">{user.name}</TableCell>
                            <TableCell>{user.email}</TableCell>
                            <TableCell>{user.created_at}</TableCell>
                            <TableCell className="text-right">{user.updated_at}</TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </AppLayout>
    );
}