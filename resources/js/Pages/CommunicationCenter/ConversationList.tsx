import React from 'react';

export default function ConversationList({ conversations, activeConversation, onSelect }) {
    if (!conversations || conversations.length === 0) {
        return <div className="p-4 text-center text-gray-500">No conversations found.</div>;
    }

    return (
        <ul className="divide-y">
            {conversations.map((conv) => (
                <li 
                    key={conv.id} 
                    onClick={() => onSelect(conv)}
                    className={`p-4 cursor-pointer hover:bg-gray-50 ${activeConversation?.id === conv.id ? 'bg-blue-50' : ''}`}
                >
                    <div className="flex justify-between">
                        <span className="font-semibold text-gray-900">{conv.subject || 'No Subject'}</span>
                        <span className="text-sm text-gray-500">{conv.status}</span>
                    </div>
                    <div className="text-sm text-gray-600 mt-1 truncate">
                        {conv.latest_message ? conv.latest_message.content_original : 'No messages yet...'}
                    </div>
                </li>
            ))}
        </ul>
    );
}
