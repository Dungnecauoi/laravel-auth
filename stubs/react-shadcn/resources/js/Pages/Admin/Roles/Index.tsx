import { Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Pagination, { type PaginationLink } from '@/components/Pagination';

interface RoleRow {
    id: number;
    name: string;
    label: string | null;
    users_count: number;
}

export default function Index({ roles }: { roles: { data: RoleRow[]; links: PaginationLink[] } }) {
    function destroy(id: number) {
        if (confirm('Xoá vai trò này?')) {
            router.delete(`/admin/roles/${id}`);
        }
    }

    return (
        <AdminLayout>
            <div className="mb-4 flex items-center justify-between">
                <h2 className="text-2xl font-semibold">Vai trò</h2>
                <Link href="/admin/roles/create">
                    <Button>Thêm vai trò</Button>
                </Link>
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Tên</TableHead>
                        <TableHead>Nhãn</TableHead>
                        <TableHead>Số người dùng</TableHead>
                        <TableHead />
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {roles.data.length === 0 ? (
                        <TableRow>
                            <TableCell colSpan={4} className="text-center text-muted-foreground">
                                Chưa có vai trò nào.
                            </TableCell>
                        </TableRow>
                    ) : (
                        roles.data.map((role) => (
                            <TableRow key={role.id}>
                                <TableCell className="font-medium">{role.name}</TableCell>
                                <TableCell>{role.label}</TableCell>
                                <TableCell>{role.users_count}</TableCell>
                                <TableCell className="text-right">
                                    <Link href={`/admin/roles/${role.id}/edit`} className="mr-3 underline underline-offset-4">
                                        Sửa
                                    </Link>
                                    <Button variant="link" className="h-auto p-0 text-destructive" onClick={() => destroy(role.id)}>
                                        Xoá
                                    </Button>
                                </TableCell>
                            </TableRow>
                        ))
                    )}
                </TableBody>
            </Table>

            <Pagination links={roles.links} />
        </AdminLayout>
    );
}
