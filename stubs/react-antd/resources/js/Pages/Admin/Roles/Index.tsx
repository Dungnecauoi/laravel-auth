import { Link, router } from '@inertiajs/react';
import { Button, Table, Typography } from 'antd';
import Pagination, { type PaginationLink } from '@/components/Pagination';
import AdminLayout from '@/Layouts/AdminLayout';

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
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 }}>
                <Typography.Title level={2} style={{ margin: 0 }}>
                    Vai trò
                </Typography.Title>
                <Link href="/admin/roles/create">
                    <Button type="primary">Thêm vai trò</Button>
                </Link>
            </div>

            <Table
                dataSource={roles.data}
                rowKey="id"
                pagination={false}
                columns={[
                    { title: 'Tên', dataIndex: 'name' },
                    { title: 'Nhãn', dataIndex: 'label' },
                    { title: 'Số người dùng', dataIndex: 'users_count' },
                    {
                        title: '',
                        key: 'actions',
                        align: 'right',
                        render: (_, row: RoleRow) => (
                            <>
                                <Link href={`/admin/roles/${row.id}/edit`}>Sửa</Link>{' '}
                                <Button type="link" danger onClick={() => destroy(row.id)}>
                                    Xoá
                                </Button>
                            </>
                        ),
                    },
                ]}
            />

            <Pagination links={roles.links} />
        </AdminLayout>
    );
}
