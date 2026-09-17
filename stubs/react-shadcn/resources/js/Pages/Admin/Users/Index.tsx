import { Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Pagination, { type PaginationLink } from '@/components/Pagination';

interface UserRow {
    id: number;
    name: string;
    email: string;
    roles: { id: number; label: string }[];
}

interface Props {
    users: { data: UserRow[]; links: PaginationLink[] };
    search: string;
}

export default function Index({ users, search }: Props) {
    const [q, setQ] = useState(search);

    function submitSearch(e: React.FormEvent) {
        e.preventDefault();
        router.get('/admin/users', { q: q || undefined }, { preserveState: true });
    }

    function destroy(id: number) {
        if (confirm('Xoá người dùng này?')) {
            router.delete(`/admin/users/${id}`);
        }
    }

    return (
        <AdminLayout>
            <div className="mb-4 flex items-center justify-between">
                <h2 className="text-2xl font-semibold">Người dùng</h2>
                <Link href="/admin/users/create">
                    <Button>Thêm người dùng</Button>
                </Link>
            </div>

            <form onSubmit={submitSearch} className="mb-4 max-w-sm">
                <Input
                    value={q}
                    onChange={(e) => setQ(e.target.value)}
                    placeholder="Tìm theo tên hoặc email..."
                />
            </form>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Tên</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Vai trò</TableHead>
                        <TableHead />
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {users.data.length === 0 ? (
                        <TableRow>
                            <TableCell colSpan={4} className="text-center text-muted-foreground">
                                Chưa có người dùng nào.
                            </TableCell>
                        </TableRow>
                    ) : (
                        users.data.map((user) => (
                            <TableRow key={user.id}>
                                <TableCell className="font-medium">{user.name}</TableCell>
                                <TableCell>{user.email}</TableCell>
                                <TableCell>
                                    <div className="flex flex-wrap gap-1">
                                        {user.roles.map((r) => (
                                            <Badge key={r.id} variant="secondary">
                                                {r.label}
                                            </Badge>
                                        ))}
                                    </div>
                                </TableCell>
                                <TableCell className="text-right">
                                    <Link href={`/admin/users/${user.id}/edit`} className="mr-3 underline underline-offset-4">
                                        Sửa
                                    </Link>
                                    <Button variant="link" className="h-auto p-0 text-destructive" onClick={() => destroy(user.id)}>
                                        Xoá
                                    </Button>
                                </TableCell>
                            </TableRow>
                        ))
                    )}
                </TableBody>
            </Table>

            <Pagination links={users.links} />
        </AdminLayout>
    );
}
