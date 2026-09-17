import { router } from '@inertiajs/react';
import { Button, List, Tag } from 'antd';

interface Session {
    id: string;
    is_current_device: boolean;
    ip_address: string | null;
    user_agent: string | null;
    last_active: string;
}

export default function SessionList({ sessions }: { sessions: Session[] }) {
    function revoke(id: string) {
        router.delete(`/sessions/${id}`);
    }

    function revokeOthers(e: React.FormEvent) {
        e.preventDefault();
        router.delete('/sessions/other');
    }

    return (
        <div>
            <List
                dataSource={sessions}
                renderItem={(session) => (
                    <List.Item
                        actions={
                            session.is_current_device
                                ? []
                                : [
                                      <Button key="logout" type="link" size="small" onClick={() => revoke(session.id)}>
                                          Đăng xuất
                                      </Button>,
                                  ]
                        }
                    >
                        <List.Item.Meta
                            title={
                                <>
                                    {session.ip_address}
                                    {session.is_current_device && (
                                        <Tag color="success" style={{ marginLeft: 8 }}>
                                            thiết bị này
                                        </Tag>
                                    )}
                                </>
                            }
                            description={`${session.user_agent ?? ''} · ${session.last_active}`}
                        />
                    </List.Item>
                )}
            />
            <form onSubmit={revokeOthers} style={{ marginTop: 16 }}>
                <Button htmlType="submit">Đăng xuất tất cả thiết bị khác</Button>
            </form>
        </div>
    );
}
