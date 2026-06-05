class MessageModel {
  final int id;
  final int senderId;
  final int receiverId;
  final int? propertyId;
  final String subject;
  final String body;
  final bool isRead;
  final String? createdAt;
  final String? updatedAt;
  final String? senderName;
  final String? senderAvatar;
  final String? receiverName;
  final String? propertyTitle;

  MessageModel({
    required this.id, required this.senderId, required this.receiverId,
    this.propertyId, required this.subject, required this.body,
    this.isRead = false, this.createdAt, this.updatedAt,
    this.senderName, this.senderAvatar, this.receiverName, this.propertyTitle,
  });

  factory MessageModel.fromJson(Map<String, dynamic> json) {
    return MessageModel(
      id: json['id'], senderId: json['sender_id'], receiverId: json['receiver_id'],
      propertyId: json['property_id'], subject: json['subject'] ?? '',
      body: json['body'] ?? '',
      isRead: json['is_read'] ?? json['isRead'] ?? false,
      createdAt: json['created_at'], updatedAt: json['updated_at'],
      senderName: json['sender'] != null ? json['sender']['name'] : null,
      senderAvatar: json['sender'] != null ? json['sender']['avatar'] : null,
      receiverName: json['receiver'] != null ? json['receiver']['name'] : null,
      propertyTitle: json['property'] != null ? json['property']['title'] : null,
    );
  }

  String get preview {
    if (body.length <= 100) return body;
    return '${body.substring(0, 100)}...';
  }
}
