import { Link, router } from '@inertiajs/react';
import { Button, Table, Typography } from 'antd';
import Pagination, { type PaginationLink } from '@/components/Pagination';
import AdminLayout from '@/Layouts/AdminLayout';

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
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 }}>
                <Typography.Title level={2} style={{ margin: 0 }}>
                    Quyền
                </Typography.Title>
                <Link href="/admin/permissions/create">
                    <Button type="primary">Thêm quyền</Button>
                </Link>
            </div>

            <Table
                dataSource={permissions.data}
                rowKey="id"
                pagination={false}
                columns={[
                    { title: 'Tên', dataIndex: 'name' },
                    { title: 'Nhãn', dataIndex: 'label' },
                    { title: 'Số vai trò', dataIndex: 'roles_count' },
                    {
                        title: '',
                        key: 'actions',
                        align: 'right',
                        render: (_, row: PermissionRow) => (
                            <>
                                <Link href={`/admin/permissions/${row.id}/edit`}>Sửa</Link>{' '}
                                <Button type="link" danger onClick={() => destroy(row.id)}>
                                    Xoá
                                </Button>
                            </>
                        ),
                    },
                ]}
            />

            <Pagination links={permissions.links} />
        </AdminLayout>
    );
}
