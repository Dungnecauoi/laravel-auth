import { Link, router } from '@inertiajs/react';
import { Button, Input, Table, Tag, Typography } from 'antd';
import Pagination, { type PaginationLink } from '@/components/Pagination';
import AdminLayout from '@/Layouts/AdminLayout';

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
    function onSearch(value: string) {
        router.get('/admin/users', { q: value || undefined }, { preserveState: true });
    }

    function destroy(id: number) {
        if (confirm('Xoá người dùng này?')) {
            router.delete(`/admin/users/${id}`);
        }
    }

    return (
        <AdminLayout>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 }}>
                <Typography.Title level={2} style={{ margin: 0 }}>
                    Người dùng
                </Typography.Title>
                <Link href="/admin/users/create">
                    <Button type="primary">Thêm người dùng</Button>
                </Link>
            </div>

            <Input.Search
                defaultValue={search}
                placeholder="Tìm theo tên hoặc email..."
                onSearch={onSearch}
                style={{ maxWidth: 320, marginBottom: 16 }}
                allowClear
            />

            <Table
                dataSource={users.data}
                rowKey="id"
                pagination={false}
                columns={[
                    { title: 'Tên', dataIndex: 'name' },
                    { title: 'Email', dataIndex: 'email' },
                    {
                        title: 'Vai trò',
                        dataIndex: 'roles',
                        render: (roles: UserRow['roles']) => roles.map((r) => <Tag key={r.id}>{r.label}</Tag>),
                    },
                    {
                        title: '',
                        key: 'actions',
                        align: 'right',
                        render: (_, row: UserRow) => (
                            <>
                                <Link href={`/admin/users/${row.id}/edit`}>Sửa</Link>{' '}
                                <Button type="link" danger onClick={() => destroy(row.id)}>
                                    Xoá
                                </Button>
                            </>
                        ),
                    },
                ]}
            />

            <Pagination links={users.links} />
        </AdminLayout>
    );
}
