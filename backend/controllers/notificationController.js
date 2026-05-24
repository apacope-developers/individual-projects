const { Notification } = require('../models');

/**
 * Get user notifications
 */
const getNotifications = async (req, res, next) => {
  try {
    const user = req.user;
    const { unread_only } = req.query;

    const whereClause = { user_id: user.id };
    if (unread_only === 'true') {
      whereClause.is_read = false;
    }

    const notifications = await Notification.findAll({
      where: whereClause,
      order: [['created_at', 'DESC']],
      limit: 50
    });

    res.status(200).json({
      success: true,
      data: notifications
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Mark notification as read
 */
const markAsRead = async (req, res, next) => {
  try {
    const { id } = req.params;

    const notification = await Notification.findByPk(id);
    if (!notification) {
      return res.status(404).json({
        success: false,
        message: 'Notification not found'
      });
    }

    // Check authorization
    if (notification.user_id !== req.user.id && req.user.role !== 'admin') {
      return res.status(403).json({
        success: false,
        message: 'Not authorized to update this notification'
      });
    }

    notification.is_read = true;
    await notification.save();

    res.status(200).json({
      success: true,
      message: 'Notification marked as read',
      data: notification
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Mark all notifications as read
 */
const markAllAsRead = async (req, res, next) => {
  try {
    const user = req.user;

    await Notification.update(
      { is_read: true },
      { where: { user_id: user.id, is_read: false } }
    );

    res.status(200).json({
      success: true,
      message: 'All notifications marked as read'
    });
  } catch (error) {
    next(error);
  }
};

/**
 * Get unread notification count
 */
const getUnreadCount = async (req, res, next) => {
  try {
    const user = req.user;

    const count = await Notification.count({
      where: {
        user_id: user.id,
        is_read: false
      }
    });

    res.status(200).json({
      success: true,
      data: { count }
    });
  } catch (error) {
    next(error);
  }
};

module.exports = {
  getNotifications,
  markAsRead,
  markAllAsRead,
  getUnreadCount
};
