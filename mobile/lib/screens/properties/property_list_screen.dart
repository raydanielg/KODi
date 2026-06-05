import 'package:flutter/material.dart';

class PropertyListScreen extends StatelessWidget {
  const PropertyListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Mali')),
      body: const Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.home_work_outlined, size: 64, color: Colors.grey),
            SizedBox(height: 16),
            Text(
              'Maudhui yanakuja...',
              style: TextStyle(fontSize: 18, color: Colors.grey),
            ),
          ],
        ),
      ),
    );
  }
}
