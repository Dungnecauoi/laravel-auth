import { Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Pagination, { type PaginationLink } from '@/components/Pagination';

interface PermissionRow {
    id: number;
    name: string;
    label: string | null;
    roles_count: number;
}

export default function Index({ permissions }: { permissions: { data: PermissionRow[]; links: PaginationLink[] } }) {
    function destroy(id: number) {
        if (confirm('Xoá quyền này?')) {
            router.delete(`/admin/permissions/${id}`);
        }
    }

    return (
        <AdminLayout>
            <div className="mb-4 flex items-center justify-between">
                <h2 className="text-2xl font-semibold">Quyền</h2>
                <Link href="/admin/permissions/create">
                    <Button>Thêm quyền</Button>
                </Link>
            </div>

            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Tên</TableHead>
                        <TableHead>Nhãn</TableHead>
                        <TableHead>Số vai trò</TableHead>
                        <TableHead />
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {permissions.data.length === 0 ? (
                        <TableRow>
                            <TableCell colSpan={4} className="text-center text-muted-foreground">
                                Chưa có quyền nào.
                            </TableCell>
                        </TableRow>
                    ) : (
                        permissions.data.map((permission) => (
                            <TableRow key={permission.id}>
                                <TableCell className="font-medium">{permission.name}</TableCell>
                                <TableCell>{permission.label}</TableCell>
                                <TableCell>{permission.roles_count}</TableCell>
                                <TableCell className="text-right">
                                    <Link
                                        href={`/admin/permissions/${permission.id}/edit`}
                                        className="mr-3 underline underline-offset-4"
                                    >
                                        Sửa
                                    </Link>
                                    <Button
                                        variant="link"
                                        className="h-auto p-0 text-destructive"
                                        onClick={() => destroy(permission.id)}
                                    >
                                        Xoá
                                    </Button>
                                </TableCell>
                            </TableRow>
                        ))
                    )}
                </TableBody>
            </Table>

            <Pagination links={permissions.links} />
        </AdminLayout>
    );
}
